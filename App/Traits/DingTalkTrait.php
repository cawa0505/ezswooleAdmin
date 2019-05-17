<?php
/**
 * 钉钉通知
 * @category Category
 * @author qap <qiuapeng921@163.com>
 * @Date 2019/3/29 9:40
 */

namespace App\Traits;

use EasySwoole\EasySwoole\Config;
use Jormin\Dingtalk\Dingtalk;

trait DingTalkTrait
{
    /**
     * description
     *
     * @return Dingtalk
     *
     * @author  邱阿朋 <qiuapeng921@163.com>
     * @date    2019/5/17 23:52
     */
    private function init()
    {
        $web_hook  = Config::getInstance()->getConf('AliYun.Web_Hook');
        $ding_talk = new Dingtalk($web_hook);
        return $ding_talk;
    }

    /**
     * 发送文本消息
     * @param string $content 消息内容
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     * @return array
     */
    public function sendText($content, $mobiles = [], $isAtAll = false)
    {
        $ding_talk = $this->init();
        $result    = $ding_talk->sendText($content, $mobiles, $isAtAll);
        return $result;
    }

    /**
     * 发送链接消息
     * @param string $title 消息标题
     * @param string $text 消息内容。如果太长只会部分展示
     * @param string $messageUrl 点击消息跳转的URL
     * @param string $picUrl 图片URL
     * @return array
     */
    public function sendLink($title, $text, $messageUrl, $picUrl = '')
    {
        $ding_talk = $this->init();
        $result    = $ding_talk->sendLink($title, $text, $messageUrl, $picUrl);
        return $result;
    }

    /**
     * 发送Markdown消息
     * @param string $title 首屏会话透出的展示内容
     * @param string $text markdown格式的消息
     * @param array $mobiles 被@人的手机号(在content里添加@人的手机号)
     * @param bool $isAtAll @所有人时：true，否则为：false
     * @return array
     */
    public function sendMarkdown($title, $text, $mobiles = [], $isAtAll = false)
    {
        $ding_talk = $this->init();
        $result    = $ding_talk->sendMarkdown($title, $text, $mobiles, $isAtAll);
        return $result;
    }
}
