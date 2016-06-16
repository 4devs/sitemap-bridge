<?php

namespace FDevs\Bridge\Sitemap\Controller;

use FDevs\Sitemap\SitemapManager;
use Symfony\Component\HttpFoundation\Response;

class SitemapController
{
    /**
     * @var SitemapManager
     */
    private $manager;

    /**
     * @var array
     */
    private $params = [];

    /**
     * SitemapController constructor.
     *
     * @param SitemapManager $manager
     * @param array          $params
     */
    public function __construct(SitemapManager $manager, array $params)
    {
        $this->manager = $manager;
        $this->params = $params;
    }

    /**
     * @return Response
     * @throws \FDevs\Sitemap\Exception\FactoryNotFoundException
     */
    public function indexAction()
    {
        return new Response($this->manager->get('sitemap')->xmlString($this->params), 200, ['Content-Type' => 'application/xml']);
    }
}
