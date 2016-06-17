<?php

namespace FDevs\Bridge\Sitemap\Adapter;

use Doctrine\ODM\MongoDB\DocumentRepository;
use FDevs\Sitemap\Model\ChangeFrequency;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class DocumentRouting extends AbstractRouting
{
    /**
     * @var DocumentRepository
     */
    private $repo;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $securityContext;

    /**
     * @var array
     */
    private $default = [
        'priority' => 0.7,
        'changefreq' => ChangeFrequency::WEEKLY,
    ];

    /**
     * {@inheritdoc}
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, $eventDispatcher, DocumentRepository $repo, AuthorizationCheckerInterface $securityContext = null)
    {
        parent::__construct($urlGenerator, $eventDispatcher);
        $this->securityContext = $securityContext;
        $this->repo = $repo;
    }

    /**
     * @param string     $name
     * @param mixed|null $value null if need delete default value
     *
     * @return  $this
     */
    public function setDefault($name, $value)
    {
        $this->default[$name] = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getItemList(array $params = [])
    {
        return $this->repo->createQueryBuilder()->eagerCursor(true)->getQuery()->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function isGranted($item, array $params)
    {
        return parent::isGranted($item, $params) && method_exists($item, 'getName');
    }

    /**
     * {@inheritdoc}
     */
    public function createUrl($name, array $params = [], $item)
    {
        $url = null;
        $name = $item->getName();
        foreach ($this->default as $property => $value) {
            if ($value !== null && !$item->hasDefault($property)) {
                $item->setDefault($property, $value);
            }
        }
        if ($this->securityContext && method_exists($item, 'getContent')) {
            try {
                $content = $item->getContent();
                if ($content && $this->securityContext->isGranted('VIEW_ANONYMOUS', $content)) {
                    $item->setDefault('priority', 0.7);
                    $item->setDefault('changefreq', ChangeFrequency::WEEKLY);
                    $url = parent::createUrl($name, $params, $item);
                }
            } catch (\Exception $e) {
            }
        } else {
            $url = parent::createUrl($name, $params, $item);
        }

        return $url;
    }
}
