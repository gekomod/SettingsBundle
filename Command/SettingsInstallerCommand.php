<?php

namespace Gekomod\SettingsBundle\Command;

use Gekomod\SettingsBundle\Entity\Settings;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Psr\Container\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * @author zaba <zaba141@o2.pl>
 */
//final class SettingsInstallerCommand extends Command
class SettingsInstallerCommand extends Command 
{
	
	 /** @var ContainerInterface $container */
    protected $container;
	
	/** @var Doctrine $doctrine */
    protected $entity_manager;

    public function __construct(EntityManager $entity_manager,$container)
    {
        parent::__construct();
        $this->container = $container;
		$this->doctrine = $entity_manager;
		$this->connection = $this->container->get('doctrine.orm.default_entity_manager')->getConnection()->isConnected();
    }
	
    protected function configure(): void
    {
        $this
            ->setName('settings:install')
            ->setDescription('Install Settings For Bundle');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->title($output);

        $ems = $this->doctrine;
		
		if ($this->connection) {
            $this->info('You Are Not Connected To DataBase Exit from Installation', $output);

            return false;
        }
        $this->success('You Are Connected To Database', $output);
		
		$entityManager = $this->container->get('doctrine.orm.default_entity_manager');

        $repository = $ems->getRepository(Settings::class);

        $template = $repository->findOneByName('template');

        if (!$template) {
            $settings = new Settings();
            $settings->setName('template');
            $settings->setVar('default');
            $settings->setActive(1);
            $this->doctrine->persist($settings);
            $this->doctrine->flush();

            $settings = new Settings();
            $settings->setName('debug');
            $settings->setVar('true');
            $settings->setActive(1);
            $this->doctrine->persist($settings);
            $this->doctrine->flush();

            $settings = new Settings();
            $settings->setName('seo_title');
            $settings->setVar('Your Site title');
            $settings->setActive(1);
            $this->doctrine->persist($settings);
            $this->doctrine->flush();

            $settings = new Settings();
            $settings->setName('seo_meta');
            $settings->setVar('Site Meta Data');
            $settings->setActive(1);
            $this->doctrine->persist($settings);
            $this->doctrine->flush();

            $settings = new Settings();
            $settings->setName('seo_description');
            $settings->setVar('Description');
            $settings->setActive(1);
            $this->doctrine->persist($settings);
            $this->doctrine->flush();
        }
		
        $success = $this->install();

        if ($success) {
            $this->success('Settings Bundle has been successfully installed...', $output);
        }
        $this->info('Settings Bundle installation has been skipped...', $output);

        return 0;
    }

    public function install()
    {
        return true;
    }

    private function title(OutputInterface $output): void
    {
        $output->writeln(
            [
                '----------------------------',
                '| Settings Bundle Installer |',
                '----------------------------',
                '',
            ]
        );
    }

    private function success(string $message, OutputInterface $output): void
    {
        $this->block('[OK] - '.$message, $output, 'green', 'black');
    }

    private function info(string $message, OutputInterface $output): void
    {
        $this->block('[INFO] - '.$message, $output, 'yellow', 'black');
    }

    private function block(
        string $message,
        OutputInterface $output,
        string $background = null,
        string $font = null
    ): void {
        $options = [];

        if (null !== $background) {
            $options[] = 'bg='.$background;
        }

        if (null !== $font) {
            $options[] = 'fg='.$font;
        }

        $pattern = ' %s ';

        if (!empty($options)) {
            $pattern = '<'.implode(';', $options).'>'.$pattern.'</>';
        }

        $output->writeln($block = sprintf($pattern, str_repeat(' ', strlen($message))));
        $output->writeln(sprintf($pattern, $message));
        $output->writeln($block);
    }
}
