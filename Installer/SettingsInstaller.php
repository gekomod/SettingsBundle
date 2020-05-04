<?php

namespace Gekomod\SettingsBundle\Installer;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

final class SettingsInstaller
{
    const NOTIFY_CLEAR = 'clear';
    
    public function __construct(array $options = [])
    {
        
    }
    
    public function install(array $options = []): bool
    {

        $this->notify('Install Settings Bundle', self::NOTIFY_CLEAR, '');
        
        $this->notify('Try Connect To DataBase', self::NOTIFY_CLEAR, '');

        return true;
    }
    
    /**
     * @param mixed $data
     *
     * @return mixed
     */
    private function notify(callable $notifier = null, string $type, $data = null)
    {
        if (null !== $notifier) {
            return $notifier($type, $data);
        }
    }
    
    private function createException(string $message): \RuntimeException
    {
        $error = error_get_last();

        if (isset($error['message'])) {
            $message .= sprintf(' (%s)', $error['message']);
        }

        return new \RuntimeException($message);
    }
}
