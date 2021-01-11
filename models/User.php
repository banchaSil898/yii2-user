<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $date_of_birth
 * @property int|null $gender
 * @property string|null $social
 * @property string|null $profile_image
 * @property string $authKey
 * @property string $accessToken
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{   
    public $password_confirm;
    public $uploaded_image;
    public $dateForScreen = '01/01/1990';
    public $username;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['email', 'password','gender', 'firstname', 'lastname',], 'required'],
            [['date_of_birth','social'], 'safe'],
            [['email', 'password', 'firstname', 'lastname', 'profile_image', 'authKey', 'accessToken'], 'string', 'max' => 255],
            [['dateForScreen'],'string','max'=> 12],
            [['uploaded_image'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['email'],'email'],
            [['password_confirm'], 'compare', 'compareAttribute'=>'password'],
            [['email'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'password' => 'Password',
            'firstname' => 'First Name',
            'lastname' => 'Last Name',
            'date_of_birth' => 'Date Of Birth',
            'gender' => 'Gender',
            'social_array' => 'Social',
            'profile_image' => 'Profile Image',
        ];
    }
    public function behaviors()
    {
        return [
            [
                'class' => AttributeBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'social',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'social',                    
                ],
                'value' => function ($event) {
                    if(isset($this->social) && $this->social != ""){
                        return implode(',', $this->social);
                    }
                },
            ],
        ];
    }
    
    public function socialToArray(){
        return $this->social = explode(',', $this->social);
    }
    public function uploadImage(){
        if($this->validate()){
            if($this->uploaded_image){
                $fileName = $this->email.'.'.$this->uploaded_image->extension;
                $this->uploaded_image->saveAs(Yii::getAlias('@webroot').'/uploads/'.$fileName);
                return $fileName;
            }
        }
        
        return $this->isNewRecord ? false : $this->getOldAttribute('profile_image');
    }

    public function getUploadPath(){
        return Yii::getAlias('@webroot').'/uploads/';
    }

    public function getUploadUrl(){
        return Yii::getAlias('@web').'/uploads/';
    }

    public function getPhotoViewer(){
        return empty($this->profile_image) ? Yii::getAlias('@web').'/img/none.png' : $this->getUploadUrl().$this->profile_image;
    }

    public function getAuthKey(): string {
        return $this->authKey;
    }

    public function getId() {
        return $this->id;
    }

    public function validateAuthKey($authKey): bool {
        return $this->authKey === $authKey;
    }

    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
        return self::findOne(['accessToken'=>$token]);
    }
    public static function findByUsername($email){
        return self::findOne(['email'=>$email]);
    }
    public function validatePassword($password){
        return password_verify($password, $this->password);
    }
}
