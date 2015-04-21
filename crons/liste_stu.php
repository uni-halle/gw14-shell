#!/usr/bin/env php
<?php

namespace mlu\groupwise\crons;

use mlu\common,
    mlu\groupwise\apiResult,
    mlu\groupwise\xsd\membership,
    mlu\groupwise\xsd\restAddressable;

require_once 'application.php';
require_once 'helpers/worker.cls.php';

$worker = new \worker(20);

/** @var $stu_group membership **/
$stu_group=(object)array(@participation=>@BLIND_COPY,@id=>"GROUP.gwdomm.pom00.nutzer_stu");

$u = Baseobjects('count=1000','DOMAIN.gwdoms');
do {
    $u->each(function($u)use($worker,$stu_group){
        /** @var $u apiResult|restAddressable */
        $worker->enqueue(function()use($u,$stu_group){
            $memberships=$u->url().'/groupmemberships';
            //echo $memberships."\n";
            if(0===stripos($u->id,@USER)) $u->requestApiInstance($memberships,@POST,$stu_group);
            /** @var $u apiResult|restAddressable */
            $u=$u->url(@GET);
            echo "{$u->id} - {$u->emailAddresses[0]}\n";
        })->work(false);
    });
} while($u=$u->nextListPage());
$worker->work()->wait();