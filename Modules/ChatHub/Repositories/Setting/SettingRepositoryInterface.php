<?php

namespace Modules\ChatHub\Repositories\Setting;

interface SettingRepositoryInterface
{
    public function createChannel($data);
    public function getChannelList($id);
    public function subscribeChannel($id);
    public function unsubscribeChannel($id);
    public function showOption($id, $check);
    public function getChannel($id);
    public function saveChannel(array $data,$id);
}