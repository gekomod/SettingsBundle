php

/*
 * This file is part of the FOSCKEditor Bundle.
 *
 * (c) 2018 - present  Friends of Symfony
 * (c) 2009 - 2017     Eric GELOEN <geloen.eric@gmail.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Gekomod\SettingsBundle\Command;

use Gekomod\SettingsBundle\Installer\SettingsInstaller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * @author zaba <zaba141@o2.pl>
 */
final class SettingsInstallerCommand extends Command
{
    /**
     * @var CKEditorInstaller
     */
    private $installer;

    public function __construct(SettingsInstaller $installer)
    {
        parent::__construct();

        $this->installer = $installer;
    }

    protected function configure(): void
    {
        $this
            ->setName('settings:install')
            ->setDescription('Install Settings For Bundle')
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->title($output);

        $success = $this->installer->install($this->createOptions($input, $output));

        if ($success) {
            $this->success('Settings Bundle has been successfully installed...', $output);
        } else {
            $this->info('Settings Bundle installation has been skipped...', $output);
        }

        return 0;
    }

    private function createOptions(InputInterface $input, OutputInterface $output): array
    {
        $options = ['notifier' => $this->createNotifier($input, $output)];

        return array_filter($options);
    }

    private function createNotifier(InputInterface $input, OutputInterface $output): \Closure
    {
        $barOutput = $input->getOption('no-progress-bar') ? new NullOutput() : $output;

        $clear = new ProgressBar($barOutput);

        return 'OK';
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

    private function comment(string $message, OutputInterface $output): void
    {
        $output->writeln(' // '.$message);
        $output->writeln('');
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

    private function finishProgressBar(ProgressBar $progress, OutputInterface $output): void
    {
        $progress->finish();
        $output->writeln(['', '']);
    }
}
