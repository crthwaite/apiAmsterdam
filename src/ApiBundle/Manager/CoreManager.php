<?php

namespace ApiBundle\Manager;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Translator;

abstract class CoreManager
{
    /** @var  EntityManager $em */
    protected $em;

    /** @var $class */
    protected $class;

    /** @var Translator $translator */
    protected $translator;

    /** @var Session $session */
    protected $session;

    /** @var ObjectRepository $repository */
    protected $repository;

    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * Realiza la persistencia de los cambios realizados sobre las entidades
     */
    public function flush()
    {
        $this->em->flush();
    }

    /**
     * Persiste un objeto
     *
     * @param      $object
     * @param bool $flush
     *
     * @return void
     */
    public function persist($object, $flush = false)
    {
        $this->em->persist($object);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * Elimina un objeto
     *
     * @param      $object
     * @param bool $flush
     *
     * @return void
     */
    public function remove($object, $flush = false)
    {
        $this->em->remove($object);

        if ($flush) {
            $this->flush();
        }
    }

    /**
     * @return ObjectRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return EntityManager
     */
    protected function getManager()
    {
        return $this->em;
    }

    /**
     * @param EntityManager $em
     */
    public function setEntityProperties(EntityManager $em)
    {
        $this->em         = $em;
        $this->repository = $this->em->getRepository($this->class);
    }

    /**
     * @param Translator $translator
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * @param Session $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }
}