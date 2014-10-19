<?php

namespace VladChange\StoreBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Farpost\StoreBundle\Entity\Version;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Image
 *
 * @ORM\Table(name="images")
 * @ORM\Entity(repositoryClass="VladChange\StoreBundle\Entity\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
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
     * @ORM\Column(type="integer")
     */
    protected $uploadTS;

    /**
     * @Assert\File(maxSize="60000000")
     */
    protected $file;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    protected $extension;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $resized = false;

    protected $temp;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
    */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image extension
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->extension = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
    */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $this->extension = $this->getFile()->guessExtension();
        }
        $dt = new \DateTime();
        $this->uploadTS = $dt->getTimestamp();
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
    */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image extension
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method does
        $this->getFile()->move(
            $this->getUploadRootDir(),
            "img_" . $this->id . '.' . $this->getFile()->guessExtension()
        );
        $this->setFile(null);
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->extension
            ? null
            : $this->getUploadRootDir() . '/' . "img_{$this->id}.{$this->extension}";
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    // public function getWebPath()
    // {
        // return null === $this->extension
            // ? null
            // : $this->getUploadDir().'/'.$this->extension;
    // }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return WEB_DIRECTORY . '/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/images';
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
     * Set uploadTS
     *
     * @param integer $uploadTS
     * @return Image
     */
    public function setUploadTS($uploadTS)
    {
        $this->uploadTS = $uploadTS;

        return $this;
    }

    /**
     * Get uploadTS
     *
     * @return integer 
     */
    public function getUploadTS()
    {
        return $this->uploadTS;
    }

    /**
     * Set extension
     *
     * @param string $extension
     * @return Image
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string 
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set resized
     *
     * @param boolean $resized
     * @return Image
     */
    public function setResized($resized)
    {
        $this->resized = $resized;

        return $this;
    }

    /**
     * Get resized
     *
     * @return boolean 
     */
    public function getResized()
    {
        return $this->resized;
    }
}
