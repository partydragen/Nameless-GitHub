<?php
/*
 *  Made by Partydragen
 *  https://github.com/partydragen/Nameless-GitHub
 *  https://partydragen.com/
 *  NamelessMC version 2.1.2
 *
 *  GitHub module file
 */

class GitHub_Module extends Module {

    private Language $_language;

    public function __construct(Language $language) {
        $name = 'GitHub';
        $author = '<a href="https://partydragen.com" target="_blank" rel="nofollow noopener">Partydragen</a>';
        $module_version = '1.0.0';
        $nameless_version = '2.1.2';

        parent::__construct($this, $name, $author, $module_version, $nameless_version);

        NamelessOAuth::getInstance()->registerProvider('github', 'GitHub', [
            'class' => \League\OAuth2\Client\Provider\Github::class,
            'user_id_name' => 'id',
            'scope_id_name' => 'user:email',
            'icon' => 'fab fa-github',
            'verify_email' => static fn () => true,
        ]);

        Integrations::getInstance()->registerIntegration(new GitHubIntegration($language));
    }

    public function onInstall() {
        // No actions necessary
    }

    public function onUninstall() {
        // No actions necessary
    }

    public function onEnable() {
        // No actions necessary
    }

    public function onDisable() {
        // No actions necessary
    }

    public function onPageLoad($user, $pages, $cache, $smarty, $navs, $widgets, $template) {
        // No actions necessary
    }

    public function getDebugInfo(): array {
        return [];
    }

    /*
     *  Check for Module updates
     *  Returns JSON object with information about any updates
     */
    private static function updateCheck() {
        $current_version = Settings::get('nameless_version');
        $uid = Settings::get('unique_id');

        $enabled_modules = Module::getModules();
        foreach ($enabled_modules as $enabled_item) {
            if ($enabled_item->getName() == 'GitHub') {
                $module = $enabled_item;
                break;
            }
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, 'https://api.partydragen.com/stats.php?uid=' . $uid . '&version=' . $current_version . '&module=GitHub&module_version='.$module->getVersion());

        $update_check = curl_exec($ch);
        curl_close($ch);

        $info = json_decode($update_check);
        if (isset($info->message)) {
            die($info->message);
        }

        return $update_check;
    }
}