<?php

declare(strict_types=1);

namespace Aboshxm2\libasyncql_query_bug;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use poggit\libasynql\DataConnector;
use poggit\libasynql\libasynql;
use poggit\libasynql\SqlError;

class Main extends PluginBase
{
    private DataConnector $database;

    protected function onEnable(): void
    {
        $this->database = libasynql::create($this, [
            "type" => "sqlite",
            "sqlite" => ["file" => "data.sqlite"],
            "worker-limit" => 1
        ], [
            "sqlite" => "sqlite.sql"
        ]);

        $this->database->executeGeneric("libasyncql_query_bug.init", [], null, function (SqlError $error): void {
            throw new $error;
        });
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if(!isset($args[0])) {
            $sender->sendMessage("/test (insert|select)");
            return true;
        }

        if($args[0] === "insert") {

            if(count($args) < 3) {
                $sender->sendMessage("/test insert (playerName) (kills)");
                return true;
            }

            $this->database->executeInsert("libasyncql_query_bug.insert", ["playerName" => $args[1], "kills" => (int)$args[2]], function (int $_) {
                var_dump("insert query executed successfully.");
            }, function (SqlError $error): void {
                throw new $error;
            });

        }elseif($args[0] === "select") {

            if(count($args) < 2) {
                $sender->sendMessage("/test select (playerName)");
                return true;
            }

            $this->database->executeSelect("libasyncql_query_bug.select", ["playerName" => $args[1]], function (array $rows) {
                var_dump("select query executed successfully.");
                var_dump($rows);
            }, function (SqlError $error): void {
                throw new $error;
            });
        }

        return true;
    }
}