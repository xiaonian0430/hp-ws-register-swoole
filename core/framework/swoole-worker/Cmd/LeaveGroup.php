<?php

declare(strict_types=1);

namespace HP\Swoole\Cmd;

use Swoole\Coroutine\Server\Connection;
use HP\Swoole\Interfaces\CmdInterface;
use HP\Swoole\Gateway;

class LeaveGroup implements CmdInterface
{
    public static function getCommandCode(): int
    {
        return 18;
    }

    public static function encode(int $fd, string $group): string
    {
        return pack('CN', self::getCommandCode(), $fd) . $group;
    }

    public static function decode(string $buffer): array
    {
        $res = unpack('Nfd', $buffer);
        $res['group'] = substr($buffer, 4);
        return $res;
    }

    public static function execute(Gateway $gateway, Connection $conn, string $buffer)
    {
        $data = self::decode($buffer);
        if (isset($gateway->fd_list[$data['fd']])) {
            unset($gateway->group_list[$data['group']][$data['fd']]);
            if (isset($gateway->group_list[$data['group']]) && !$gateway->group_list[$data['group']]) {
                unset($gateway->group_list[$data['group']]);
            }
            unset($gateway->fd_list[$data['fd']]['group_list'][$data['group']]);
        }
    }
}
