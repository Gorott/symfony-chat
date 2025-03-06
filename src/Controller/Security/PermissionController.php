<?php

declare(strict_types=1);

namespace App\Controller\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PermissionController extends AbstractController
{

    public function __construct(
        protected EntityManagerInterface $entityManager,
        protected AdapterInterface $cache
    )
    {
    }

    /**
     * @Route("/permission/{entity}/{id}/{permission}", name="app_check_permission", methods={"GET"})
     */
    public function __invoke(string $entity, string $id, string $permission): JsonResponse
    {
        $cacheKey = sprintf('permission_%s_%d_%s', $entity, $id, $permission);

        $cachedPermission = $this->cache->getItem($cacheKey);

        if ($cachedPermission->isHit()) {
            return new JsonResponse(['isGranted' => $cachedPermission->get()]);
        }

        $entityClass = 'App\Entity\\' . $entity;

        if (!class_exists($entityClass)) {
            return new JsonResponse(['isGranted' => false]);
        }

        $isGranted = $this->isGranted($permission, $this->entityManager->getRepository($entityClass)->find($id));
        $cachedPermission->set($isGranted);
        $this->cache->save($cachedPermission);
        
        return new JsonResponse(['isGranted' => $isGranted]);
    }
}
