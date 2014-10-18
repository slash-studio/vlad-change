<?php

namespace VladChange\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Placemark
 *
 * @ORM\Table(name="placemarks")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Placemark
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
     * @ORM\Column(name="name", type="string", length=200)
     */
    protected $name;

    /**
     * @var double
     *
     * @ORM\Column(name="lat", type="float", nullable=true)
     */
    protected $lat; // x

    /**
     * @var double
     *
     * @ORM\Column(name="lon", type="float", nullable=true)
     */
    protected $lon; // y

    /**
     * @var DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    protected $createDate;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="die_date", type="date")
     */
    protected $dieDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="limit_voice", type="integer")
     */
    protected $limitVoice;

    /**
     * @var string
     *
     * @ORM\Column(name="short_desc", type="string", length=200)
     */
    protected $shortDesc;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="text")
     */
    protected $desc;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;


    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        $this->createDate = new \DateTime();
        $this->dieDate = clone $this->createDate;
        $this->dieDate->add(new \DateInterval("P30D"));
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
     * Set lat
     *
     * @param float $lat
     * @return Placemark
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param float $lon
     * @return Placemark
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return float
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Placemark
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set create_date
     *
     * @param \DateTime $createDate
     * @return Placemark
     */
    public function setCreateDate($createDate)
    {
        $this->createDate = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Set die_date
     *
     * @param \DateTime $dieDate
     * @return Placemark
     */
    public function setDieDate($dieDate)
    {
        $this->dieDate = $dieDate;

        return $this;
    }

    /**
     * Get die_date
     *
     * @return \DateTime
     */
    public function getDieDate()
    {
        return $this->dieDate;
    }

    /**
     * Set limitVoice
     *
     * @param integer $limitVoice
     * @return Placemark
     */
    public function setLimitVoice($limitVoice)
    {
        $this->limitVoice = $limitVoice;

        return $this;
    }

    /**
     * Get limit_voice
     *
     * @return integer
     */
    public function getLimitVoice()
    {
        return $this->limitVoice;
    }

    /**
     * Set short_desc
     *
     * @param string $shortDesc
     * @return Placemark
     */
    public function setShortDesc($shortDesc)
    {
        $this->shortDesc = $shortDesc;

        return $this;
    }

    /**
     * Get short_desc
     *
     * @return string
     */
    public function getShortDesc()
    {
        return $this->shortDesc;
    }

    /**
     * Set desc
     *
     * @param string $desc
     * @return Placemark
     */
    public function setDesc($desc)
    {
        $this->desc = $desc;

        return $this;
    }

    /**
     * Get desc
     *
     * @return string
     */
    public function getDesc()
    {
        return $this->desc;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Placemark
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
     * Set user
     *
     * @param \VladChange\StoreBundle\Entity\User $user
     * @return Placemark
     */
    public function setUser(\VladChange\StoreBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \VladChange\StoreBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get isExpired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->dieDate < $this->createDate;
    }
}
