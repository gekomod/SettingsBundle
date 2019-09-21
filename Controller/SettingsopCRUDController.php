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

class SettingsopCRUDController extends CRUDController
{

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

        $this->addFlash('sonata_flash_success',$content);

        return $this->redirectToRoute('admin_gekomod_settings_settings_list');
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