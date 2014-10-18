<?php

namespace VladChange\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * User
 *
 * @ORM\Table(name="users")
 * @ORM\Entity
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=100)
     * @Assert\NotBlank(message="Пожалуйста, введите своё имя.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min=3,
     *      max=100,
     *      minMessage="Имя должно состоять больше чем из 2-х букв",
     *      maxMessage="Имя должно состоять не более чем из 100 букв",
     *      groups={"Registration", "Profile"}
     * )
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="string", length=100)
     * @Assert\NotBlank(message="Пожалуйста, введите свою фамилию.", groups={"Registration", "Profile"})
     * @Assert\Length(
     *      min=3,
     *      max=100,
     *      minMessage="Фамилия должна состоять больше чем из 2-х букв",
     *      maxMessage="Фамилия должна состоять не более чем из 100 букв",
     *      groups={"Registration", "Profile"}
     * )
     */
    protected $surname;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_proj_amount", type="integer")
     */
    protected $maxProjAmount = 3;

    /**
     * @ORM\OneToMany(targetEntity="Placemark", mappedBy="user")
     */
    protected $projects;

    public function __construct() {
        parent::__construct();
        $this->projects = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
        $this->setUsername($email);

        return $this;
    }

    /**
     * Add projects
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $projects
     * @return User
     */
    public function addProject(\VladChange\StoreBundle\Entity\Placemark $projects)
    {
        $this->projects[] = $projects;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $projects
     */
    public function removeProject(\VladChange\StoreBundle\Entity\Placemark $projects)
    {
        $this->projects->removeElement($projects);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->projects;
    }

    /**
     * Set maxProjAmount
     *
     * @param integer $maxProjAmount
     * @return User
     */
    public function setMaxProjAmount($maxProjAmount)
    {
        $this->maxProjAmount = $maxProjAmount;

        return $this;
    }

    /**
     * Get maxProjAmount
     *
     * @return integer
     */
    public function getMaxProjAmount()
    {
        return $this->maxProjAmount;
    }

    /**
     * Get available amount of project that can be added
     *
     * @return bool
     */
    public function hasAvailableProjAmount()
    {
        $notExpiredProjs = $this->projects->filter(function($e) {
            return !$e->isExpired();
        });
        return $this->maxProjAmount - $notExpiredProjs->count() > 0;
    }
}
