<?php

namespace Modules\ChatHub\Repositories\Message;

interface MessageRepositoryInterface
{
   public function getListChannel($id);
   public function getListCustomer($channel_id, $filter);
   public function getListMessage($filter);
   public function addCustomer($customer, $channel_id, $filter);
   public function getToken($channel_id);
   public function sentMessage($customer_id, $message, $token);
   public function sentImage($customer_id, $image, $token);
   public function sentFile($customer_id, $file, $token);
   public function sentMessageZalo($customer_id, $message, $token);
   public function sentImageZalo($customer_id, $image, $token);
   public function sentFileZalo($customer_id, $file, $token);
   public function getCustomer($customer_id, $channel_id);
   public function getChannel($channel_id);
   public function addMessage($channel_id, $customer_id, $message_id);
   public function seenMessage($customer_id, $channel_id);
   public function updateCustomer($data);
   public function uploadImage($file);
   public function getSocialId($customer_id);
   public function createDeal($input);
   public function checkExistLead($input);
   public function createOrUpdateLead($input);
}
