<?php

declare(strict_types=1);

namespace HP\Swoole\Cmd;

use Swoole\Coroutine\Server\Connection;
use HP\Swoole\Interfaces\CmdInterface;
use HP\Swoole\Gateway;
use HP\Swoole\Protocol;

class GetUidList implements CmdInterface
{
    public static function getCommandCode(): int
    {
        return 14;
    }

    public static function encode(): string
    {
        return pack('C', self::getCommandCode());
    }

    public static function execute(Gateway $gateway, Connection $conn, string $buffer)
    {
        $buffer = '';
        foreach ($gateway->uid_list as $uid => $fd_list) {
            $buffer .= pack('C', strlen((string) $uid)) . $uid;
        }

        $conn->send(Protocol::encode($buffer));
    }
}
