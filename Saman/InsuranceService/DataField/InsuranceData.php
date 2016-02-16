<?php
namespace Tsp\Saman\InsuranceService\DataField;

use Poirot\Core\AbstractOptions;


/**
 * // another way of defining properties for just getter/setter or open options
 * @property string|void nationalCode @required description of property usage
 */
class InsuranceData extends AbstractOptions
{
    protected $nationalCode;
    /** @var string @required */
    protected $firstName;
    /** @var string @required */
    protected $lastName;
    /** @var string @required */
    protected $latinFirstName;
    /** @var string @required */
    protected $latinLastName;
    /** @var string|\DateTime @required yyyy-mm-ddThh:mm:ss (1983-08-13) */
    protected $birthDate;
    /** @var string */
    protected $mobile;
    /** @var string */
    protected $email;
    /** @var int @required Gender 1=male|2=female */
    protected $gender;
    /** @var string @required */
    protected $birthPlace;
    /** @var string @required */
    protected $passportNo;
    /** @var int @required */
    protected $countryCode;
    /** @var int @required */
    protected $durationOfStay;
    /** @var int @required visa type 1=single|2=multi */
    protected $travelKind;
    /** @var int @required description about field */
    protected $planCode;

    /**
     * @return string
     */
    public function getNationalCode()
    {
        return $this->nationalCode;
    }

    /**
     * @param string $nationalCode
     * @return $this
     */
    public function setNationalCode($nationalCode)
    {
        $this->nationalCode = $nationalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLatinFirstName()
    {
        return $this->latinFirstName;
    }

    /**
     * @param string $latinFirstName
     * @return $this
     */
    public function setLatinFirstName($latinFirstName)
    {
        $this->latinFirstName = $latinFirstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLatinLastName()
    {
        return $this->latinLastName;
    }

    /**
     * @param string $latinLastName
     * @return $this
     */
    public function setLatinLastName($latinLastName)
    {
        $this->latinLastName = $latinLastName;
        return $this;
    }

    /**
     * @return \DateTime|string
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime|string $birthDate
     * @return $this
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param string $mobile
     * @return $this
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
        return $this;
    }

    /**
     * @return string
     */
    public function getBirthPlace()
    {
        return $this->birthPlace;
    }

    /**
     * @param string $birthPlace
     * @return $this
     */
    public function setBirthPlace($birthPlace)
    {
        $this->birthPlace = $birthPlace;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassportNo()
    {
        return $this->passportNo;
    }

    /**
     * @param string $passportNo
     * @return $this
     */
    public function setPassportNo($passportNo)
    {
        $this->passportNo = $passportNo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param int $countryCode
     * @return $this
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
        return $this;
    }

    /**
     * @return int
     */
    public function getDurationOfStay()
    {
        return $this->durationOfStay;
    }

    /**
     * @param int $durationOfStay
     * @return $this
     */
    public function setDurationOfStay($durationOfStay)
    {
        $this->durationOfStay = $durationOfStay;
        return $this;
    }

    /**
     * @return int
     */
    public function getTravelKind()
    {
        return $this->travelKind;
    }

    /**
     * @param int $travelKind
     * @return $this
     */
    public function setTravelKind($travelKind)
    {
        $this->travelKind = $travelKind;
        return $this;
    }

    /**
     * @return int
     */
    public function getPlanCode()
    {
        return $this->planCode;
    }

    /**
     * @param int $planCode
     * @return $this
     */
    public function setPlanCode($planCode)
    {
        $this->planCode = $planCode;
        return $this;
    }
}
