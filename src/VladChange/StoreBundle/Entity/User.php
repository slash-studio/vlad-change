<?php

namespace VladChange\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;
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
     * @ORM\GeneratedValue(strategy="AUTO")
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
}
