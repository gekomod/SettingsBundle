<?php

namespace Gekomod\SettingsBundle\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Annotation\RouteCollection;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpClient\HttpClient;
use \Gekomod\SettingsBundle\Form\SettingsType;
use \Gekomod\SettingsBundle\Form\SettingsFormType;
use \Gekomod\SettingsBundle\Entity\Settings;

class SettingsopCRUDController extends CRUDController
{

    public function listAction()
    {
    $entityManager = $this->getDoctrine()->getManager();

    $repository = $this->getDoctrine()->getRepository('SettingsBundle:Settings');
    $rows_settings = $repository->findAll();

    $Settings = new Settings();

    foreach ($rows_settings as $k => $v) {
        $Settings->getName()->add($v);
    }
    
    $form = $this->createForm( SettingsFormType::class, $Settings,['action' => $this->generateUrl('admin_gekomod_settings_settings_save'),
                'method' => 'POST',]);
    $packages=[
               'Sonata Page' => $this->checkIsExists("sonata-project/page-bundle"),
               'Files Bundle' => $this->checkIsExists("gekomod/files-bundle"),
               'Settings Bundle' => $this->checkIsExists("gekomod/settings-bundle"),
               'Seo Bundle' => $this->checkIsExists("sonata-project/media-bundle"),
            ];

        return $this->renderWithExtraParams('@Settings/admin/index.html.twig',['form' =>  $form->createView(), 'packages'=> $packages]);
    }
    
    public function checkIsExists($name) {
        $arrBundles = $this->getPackages();
        if (!array_key_exists($name, $arrBundles))
        {
            // bundle not found
            $info = ['name' => $name,'exists' => 'Not Found'];
            return $info;
        } else {
            $arr = $arrBundles[$name];
            $info = ['name' => $arr['name'],'exists' => 'Installed','version' => $arr['version'], 'description' => $arr['description'], 'source' => $arr['source']['url']];
            return $info;
        }
    }
    
       private function getPackages()
    {
        $packages = array();

        $composerLockPath = $this->get('kernel')->getRootDir().'/../composer.lock';
        if (!file_exists($composerLockPath)) {
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
        $packages = array();
        foreach ($composerPackages as $packageConfig) {
            $package = array();
            $package['is_dev'] = $isDev;
            foreach (array('name', 'description', 'keywords', 'authors', 'version', 'license', 'homepage', 'type', 'source', 'bin', 'autoload', 'time') as $key) {
                $package[$key] = isset($packageConfig[$key]) ? $packageConfig[$key] : '';
            }

            $packages[$package['name']] = $package;
        }

        return $packages;
    }
    
    public function settingsSaveAction(Request $request) {

        $get_all = $request->request->get('settings_form');

        foreach($get_all['name'] as $a) {
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
    
    public function settingsCacheAction()
    {
        $application = new Application($this->get('kernel'));
        $application->setAutoExit(false);//exit after run
        $input = new ArrayInput([
            'command' => 'cache:clear',
            '--env'   => 'dev',
            '--no-warmup' => true
        ]);
        $output = new BufferedOutput();
        $runCode = $application->run($input, $output);
        $content = $output->fetch();

        $this->addFlash('info',$content);

        return $this->redirectToRoute('admin_gekomod_settings_list');
    }
    
    public function settingsUpdateAction() {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.github.com/repos/gekomod/SettingsBundle/tags');

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        $response = new Response($content, $statusCode);
        echo '<pre>';
        print_r($content[0]); 

        return $response;
    }

}