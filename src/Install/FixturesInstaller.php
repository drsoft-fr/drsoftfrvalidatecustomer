<?php

declare(strict_types=1);

namespace DrSoftFr\Module\ValidateCustomer\Install;

use CMS;
use Context;
use Db;
use DbQuery;
use PrestaShopDatabaseException;
use PrestaShopException;

/**
 * Installs data fixtures for the module.
 */
final class FixturesInstaller
{
    /**
     * @var int[] $configuration
     */
    private $configuration = [
        'cms_notify_id' => 0,
        'cms_not_activated_id' => 0,
    ];

    /**
     * @var Db
     */
    private $db;

    public function __construct(Db $db)
    {
        $this->db = $db;
    }

    /**
     * Installs necessary components for the software.
     *
     * @return int[] Returns the configuration settings after installation
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    public function install(): array
    {
        $this
            ->installCmsPage();

        return $this->configuration;
    }

    /**
     * Install CMS pages into the database if they do not already exist.
     *
     * This method checks the database for existing CMS pages based on link_rewrite and adds them if not found.
     *
     * @return FixturesInstaller
     *
     * @throws PrestaShopDatabaseException
     * @throws PrestaShopException
     */
    private function installCmsPage(): FixturesInstaller
    {
        $pages = [
            'cms_notify_id' => [
                'content' => '<p>Thank you for your registration.</p><p>Your registration is currently being reviewed by an administrator.</p><p>A confirmation email will be sent to you once your account is activated.</p>',
                'link_rewrite' => 'account-notify',
                'title' => 'Registration pending validation',
            ],
            'cms_not_activated_id' => [
                'content' => '<p>Your account has not been activated yet.</p><p>We invite you to try again a little later.</p>',
                'link_rewrite' => 'account-disable',
                'title' => 'Account not enable',
            ],
        ];

        foreach ($pages as $key => $page) {
            $result = $this->getCmsPage($page['link_rewrite']);

            if ($result) {
                $this->configuration[$key] = (int)$result['id_cms'];

                continue;
            }

            $cms = new CMS(null, Context::getContext()->language->id);

            $cms->link_rewrite = $page['link_rewrite'];
            $cms->meta_title = $page['title'];
            $cms->head_seo_title = $page['title'];
            $cms->content = $page['content'];
            $cms->id_cms_category = 1;
            $cms->active = 1;

            $cms->add();

            $this->configuration[$key] = (int)$cms->id;
        }

        return $this;
    }

    /**
     * Retrieves a specific CMS page from the database based on the provided link.
     *
     * @param string $link The link of the CMS page to retrieve
     *
     * @return array|bool|object|null Returns the row corresponding to the CMS page from the database, or null if not found
     */
    private function getCmsPage(string $link)
    {
        $q = new DbQuery();
        $q->select('id_cms');
        $q->from('cms_lang');
        $q->where('link_rewrite LIKE "' . $link . '"');

        return $this->db->getRow($q);
    }
}
