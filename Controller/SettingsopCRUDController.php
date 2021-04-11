<?php

namespace Gekomod\SettingsBundle\Controller;

use Gekomod\SettingsBundle\Entity\Settings;
use Gekomod\SettingsBundle\Form\SettingsFormType;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class SettingsopCRUDController extends CRUDController
{
    protected $filesystem;

    public function __construct()
    {
        $this->filesystem = new Filesystem();
    }

    public function listAction(\Symfony\Component\HttpFoundation\Request $request): \Symfony\Component\HttpFoundation\Response
    {
        $repository = $this->getDoctrine()->getRepository('SettingsBundle:Settings');
        $rows_settings = $repository->findAll();

        $Settings = new Settings();
        $link = [];

        foreach ($rows_settings as $v) {
            $Settings->getName()->add($v);
            $link[$v->name] = $v->id;
        }

        $form = $this->createForm(SettingsFormType::class, $Settings, ['action' => $this->generateUrl('admin_gekomod_settings_settings_save'),
            'method'                                                            => 'POST', ]);
        $packages = [
            'Sonata Page'     => $this->checkIsExists('sonata-project/page-bundle'),
            'Files Bundle'    => $this->checkIsExists('gekomod/files-bundle'),
            'Settings Bundle' => $this->checkIsExists('gekomod/settings-bundle'),
            'Seo Bundle'      => $this->checkIsExists('sonata-project/media-bundle'),
        ];

        if(extension_loaded('apcu'))
        {
          $apcu = "APCU enabled!";
          if (ini_get('apcu.enable_cli') != '1');
        } else {
            $apcu = "APCU NOT INSTALLED";
        }
        
        return $this->renderWithExtraParams('@Settings/admin/index.html.twig', ['form' =>  $form->createView(), 'packages'=> $packages, 'link' => $link, 'apcu' => $apcu]);
    }

    public function checkIsExists($name)
    {
        $arrBundles = $this->getPackages();
        if (!array_key_exists($name, $arrBundles)) {
            $info = ['name' => $name, 'exists' => 'Not Found'];

            return $info;
        }
        $arr = $arrBundles[$name];
        $info = ['name' => $arr['name'], 'exists' => 'Installed', 'version' => $arr['version'], 'description' => $arr['description'], 'source' => $arr['source']['url']];

        return $info;
    }

    private function getPackages()
    {
        $packages = [];

        $composerLockPath = $this->getParameter('kernel.project_dir').'/composer.lock';
        if (!$this->filesystem->exists($composerLockPath)) {
            return $packages;
        }

        $composerLockContents = json_decode(file_get_contents($composerLockPath), true);
        $prodPackages = $this->processComposerPackagesInformation($composerLockContents['packages']);
        $devPackages = $this->processComposerPackagesInformation($composerLockContents['packages-dev'], true);
        $allPackages = array_merge($prodPackages, $devPackages);
        ksort($allPackages);

        return $allPackages;
    }

    private function processComposerPackagesInformation($composerPackages, $isDev = false)
    {
        $packages = [];
        foreach ($composerPackages as $packageConfig) {
            $package = [];
            $package['is_dev'] = $isDev;
            foreach (['name', 'description', 'keywords', 'authors', 'version', 'license', 'homepage', 'type', 'source', 'bin', 'autoload', 'time'] as $key) {
                $package[$key] = isset($packageConfig[$key]) ? $packageConfig[$key] : '';
            }

            $packages[$package['name']] = $package;
        }

        return $packages;
    }

    public function settingsSaveAction(Request $request)
    {
        $get_all = $request->request->get('settings_form');

        foreach ($get_all['name'] as $a) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Settings::class)->createQueryBuilder('')
            ->update(Settings::class, 'u')
            ->set('u.var', ':var')
            ->setParameter('var', $a['var'])
            ->where('u.name = :name')
            ->setParameter('name', $a['name'])
            ->getQuery();

            $query->execute();
        }

        $this->addFlash('success', 'successfully changed the data');

        return $this->redirectToRoute('admin_gekomod_settings_list');
    }
    
    public function settingsApcuCacheAction()
    {
        if (extension_loaded('apcu')) {
            echo "APCu cache: " . apcu_clear_cache() . "\n";
            $this->addFlash('info', 'APCU Cache Removed');

        return $this->redirectToRoute('admin_gekomod_settings_list');
        }

        if (function_exists('opcache_reset')) {
            // Clear it twice to avoid some internal issues...
            opcache_reset();
            opcache_reset();
            $this->addFlash('info', 'Opcache Cache Removed');
        }
        
        $this->addFlash('info', 'APCU not Removed');

        return $this->redirectToRoute('admin_gekomod_settings_list');
    }

    public function settingsCacheAction()
    {
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false); //exit after run
        $input = new ArrayInput([
            'command'     => 'cache:clear',
            '--env'       => 'dev',
            '--no-warmup' => true,
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
        $content = $output->fetch();

        $this->addFlash('info', $content);

        return $this->redirectToRoute('admin_gekomod_settings_list');
    }
    
    public function settingsUpdateAction()
    {
        $response = HttpClient::create()->request('GET', 'https://api.github.com/repos/gekomod/SettingsBundle/tags');

        $statusCode = $response->getStatusCode();
        $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $response = new Response($content[0], $statusCode);

        return $response;
    }
}
