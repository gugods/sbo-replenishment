<?php
function get_cache_token($cache_key)
{
  $CI = &get_instance();
  $cache_value = null;
  $cache_key =  REDIS_NAME_SPACE . 'accessToken/' . md5($cache_key);
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $cache_value = $CI->redis->get($cache_key);
  }
  return  $cache_value ? json_decode($cache_value, true) : null;
}

function set_cache_token($cache_key, $cache_value, $expire_time)
{
  $CI = &get_instance();
  $cache_key =  REDIS_NAME_SPACE . 'accessToken/' . md5($cache_key);
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $CI->redis->set($cache_key, json_encode($cache_value));
    $CI->redis->expire($cache_key, $expire_time);
  }
}

function del_cache_token($cache_key)
{
  $CI = &get_instance();
  $cache_key =  REDIS_NAME_SPACE . 'accessToken/' . md5($cache_key);
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $CI->redis->del($cache_key);
  }
}

function get_cache_login($cache_key)
{
  $CI = &get_instance();
  $cache_value = null;
  $cache_key =  REDIS_NAME_SPACE . 'loginDate/' . $cache_key;
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $cache_value = $CI->redis->get($cache_key);
  }
  return  $cache_value;
}

function set_cache_login($cache_key, $cache_value)
{
  $CI = &get_instance();
  $cache_key =  REDIS_NAME_SPACE . 'loginDate/' . $cache_key;
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $CI->redis->set($cache_key, $cache_value);
  }
}

function del_cache_login($cache_key)
{
  $CI = &get_instance();
  $cache_key =  REDIS_NAME_SPACE . 'loginDate/' . $cache_key;
  if ($CI->redis->isConnect) {
    $CI->redis->select(USER_REDIS_DB);
    $CI->redis->del($cache_key);
  }
}
