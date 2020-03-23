<?php
interface IModule {
    public function callCommand($command,$params);
    public function processEvent($event,$params);
}
?>