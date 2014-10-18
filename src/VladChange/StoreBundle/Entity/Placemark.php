<?php

namespace VladChange\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Placemark
 *
 * @ORM\Table(name="placemarks")
 * @ORM\Entity
 */
class Placemark
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var double
     *
     * @ORM\Column(name="lat", type="float")
     */
    protected $lat; // x

    /**
     * @var double
     *
     * @ORM\Column(name="lon", type="float")
     */
    protected $lon; // y

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=150)
     */
    protected $author; // e-mail

    /**
     * @var DateTime
     *
     * @ORM\Column(name="create_date", type="date")
     */
    protected $create_date;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="die_date", type="date")
     */
    protected $die_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="limit_voice", type="integer")
     */
    protected $limit_voice;

    /**
     * @var string
     *
     * @ORM\Column(name="short_desc", type="string", length=200)
     */
    protected $short_desc;

    /**
     * @var string
     *
     * @ORM\Column(name="desc", type="text")
     */
    protected $desc;



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
     * @param \double $lat
     * @return Placemark
     */
    public function setLat(\double $lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return \double 
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param \double $lon
     * @return Placemark
     */
    public function setLon(\double $lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return \double 
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
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime 
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set die_date
     *
     * @param \DateTime $dieDate
     * @return Placemark
     */
    public function setDieDate($dieDate)
    {
        $this->die_date = $dieDate;

        return $this;
    }

    /**
     * Get die_date
     *
     * @return \DateTime 
     */
    public function getDieDate()
    {
        return $this->die_date;
    }

    /**
     * Set limit_voice
     *
     * @param integer $limitVoice
     * @return Placemark
     */
    public function setLimitVoice($limitVoice)
    {
        $this->limit_voice = $limitVoice;

        return $this;
    }

    /**
     * Get limit_voice
     *
     * @return integer 
     */
    public function getLimitVoice()
    {
        return $this->limit_voice;
    }

    /**
     * Set short_desc
     *
     * @param string $shortDesc
     * @return Placemark
     */
    public function setShortDesc($shortDesc)
    {
        $this->short_desc = $shortDesc;

        return $this;
    }

    /**
     * Get short_desc
     *
     * @return string 
     */
    public function getShortDesc()
    {
        return $this->short_desc;
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
}
