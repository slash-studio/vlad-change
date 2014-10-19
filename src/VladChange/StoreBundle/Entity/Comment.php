<?php

namespace VladChange\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Comment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="comments")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var Placemark
     *
     * @ORM\ManyToOne(targetEntity="Placemark", inversedBy="comments")
     * @ORM\JoinColumn(name="placemark_id", referencedColumnName="id")
     */
    protected $placemark;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=200)
     */
    protected $message;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dt", type="datetime")
     */
    protected $dt;

    /**
     * @ORM\PrePersist()
     */
    public function prePersist()
    {
        if (!$this->dt) {
            $this->dt = new \DateTime;
        }
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
     * Set message
     *
     * @param string $message
     * @return Comment
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set dt
     *
     * @param \DateTime $dt
     * @return Comment
     */
    public function setDt($dt)
    {
        $this->dt = $dt;

        return $this;
    }

    /**
     * Get dt
     *
     * @return \DateTime 
     */
    public function getDt()
    {
        return $this->dt;
    }

    /**
     * Set user
     *
     * @param \VladChange\StoreBundle\Entity\User $user
     * @return Comment
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
     * Set placemark
     *
     * @param \VladChange\StoreBundle\Entity\Placemark $placemark
     * @return Comment
     */
    public function setPlacemark(\VladChange\StoreBundle\Entity\Placemark $placemark = null)
    {
        $this->placemark = $placemark;

        return $this;
    }

    /**
     * Get placemark
     *
     * @return \VladChange\StoreBundle\Entity\Placemark 
     */
    public function getPlacemark()
    {
        return $this->placemark;
    }
}
