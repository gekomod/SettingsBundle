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

}