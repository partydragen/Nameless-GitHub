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
    private Language $_github_language;

    public function __construct(Language $language) {
        $this->_language = $language;
        $this->_github_language = $language;

        $name = 'GitHub';
        $author = '<a href="https://partydragen.com" target="_blank" rel="nofollow noopener">Partydragen</a>';
        $module_version = '1.0.1';
        $nameless_version = '2.2.0';

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
        // Check for module updates
        if (isset($_GET['route']) && $user->isLoggedIn() && $user->hasPermission('admincp.update')) {
            // Page belong to this module?
            $page = $pages->getActivePage();
            if ($page['module'] == $this->getName()) {

                $cache->setCache($this->getName() . '_module_cache');
                if ($cache->isCached('update_check')) {
                    $update_check = $cache->retrieve('update_check');
                } else {
                    $update_check = $this::updateCheck();
                    $cache->store('update_check', $update_check, 3600);
                }

                $update_check = json_decode($update_check);
                if (!isset($update_check->error) && !isset($update_check->no_update) && isset($update_check->new_version)) {
                    $template->getEngine()->addVariables([
                        'NEW_UPDATE' => (isset($update_check->urgent) && $update_check->urgent == 'true') ? $this->_github_language->get('general', 'new_urgent_update_available_x', ['module' => $this->getName()]) : $this->_github_language->get('general', 'new_update_available_x', ['module' => $this->getName()]),
                        'NEW_UPDATE_URGENT' => (isset($update_check->urgent) && $update_check->urgent == 'true'),
                        'CURRENT_VERSION' => $this->_github_language->get('general', 'current_version_x', [
                            'version' => Output::getClean($this->getVersion())
                        ]),
                        'NEW_VERSION' => $this->_github_language->get('general', 'new_version_x', [
                            'new_version' => Output::getClean($update_check->new_version)
                        ]),
                        'NAMELESS_UPDATE' => $this->_github_language->get('general', 'view_resource'),
                        'NAMELESS_UPDATE_LINK' => Output::getClean($update_check->link)
                    ]);
                }
            }
        }
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