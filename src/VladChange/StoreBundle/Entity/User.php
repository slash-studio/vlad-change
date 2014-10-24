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

    /**
     * @ORM\OneToMany(targetEntity="Placemark", mappedBy="user_financer")
     */
    protected $financeProjects;

    /**
     * @ORM\ManyToMany(targetEntity="Placemark", inversedBy="likes")
     * @ORM\JoinTable(name="likes")
     */
    protected $likes;

    /**
     * @ORM\ManyToMany(targetEntity="Placemark", inversedBy="dislikes")
     * @ORM\JoinTable(name="dislikes")
     */
    protected $dislikes;

    /**
     * @ORM\OneToOne(targetEntity="Image")
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    protected $image;

    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="user")
     */
    protected $comments;




    public function __construct() {
        parent::__construct();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->financeProjects = new ArrayCollection();
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
     * Get full user name
     *
     * @return string
     */
    public function getFullName()
    {
        return sprintf("%s %s", $this->name, $this->surname);
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

    public function getCurrentProjects()
    {
        return $this->projects->filter(function($e) {
            return !$e->getArchived();
        });
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

    /**
     * Add like
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $likes
     * @return User
     */
    public function addLike(\VladChange\StoreBundle\Entity\Placemark $like)
    {
        $this->likes[] = $like;

        return $this;
    }

    /**
     * Remove like
     *
     * @param int $projId
     */
    public function removeLike(\VladChange\StoreBundle\Entity\Placemark $like)
    {
        return $this->likes->removeElement($like);
    }

    /**
     * Get likes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Add dislikes
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $dislikes
     * @return User
     */
    public function addDislike(\VladChange\StoreBundle\Entity\Placemark $dislikes)
    {
        $this->dislikes[] = $dislikes;
        return $this;
    }

    /**
     * Set image
     *
     * @param \VladChange\StoreBundle\Entity\Image $image
     * @return User
     */
    public function setImage(\VladChange\StoreBundle\Entity\Image $image = null)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * Remove dislikes
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $dislikes
     */
    public function removeDislike(\VladChange\StoreBundle\Entity\Placemark $dislikes)
    {
        $this->dislikes->removeElement($dislikes);
    }

    /**
     * Get dislikes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDislikes()
    {
        return $this->dislikes;
    }
    /**
     * Get image
     *
     * @return \VladChange\StoreBundle\Entity\Image 
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getProjectsMapping()
    {
        $result = [];
        foreach ($this->projects->toArray() as $e) {
            $result[$e->getId()] = true;
        }
        return $result;
    }

    /**
     * Add comments
     *
     * @param \VladChange\StoreBundle\Entity\Comment $comments
     * @return User
     */
    public function addComment(\VladChange\StoreBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \VladChange\StoreBundle\Entity\Comment $comments
     */
    public function removeComment(\VladChange\StoreBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add financeProjects
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $financeProjects
     * @return User
     */
    public function addFinanceProject(\VladChange\StoreBundle\Entity\Placemark $financeProjects)
    {
        $this->financeProjects[] = $financeProjects;

        return $this;
    }

    /**
     * Remove financeProjects
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $financeProjects
     */
    public function removeFinanceProject(\VladChange\StoreBundle\Entity\Placemark $financeProjects)
    {
        $this->financeProjects->removeElement($financeProjects);
    }

    /**
     * Get financeProjects
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFinanceProjects()
    {
        return $this->financeProjects;
    }
}
