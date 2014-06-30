<?php
/**
 * User: Rottenwood
 * Date: 29.06.14
 * Time: 2:24
 */

namespace Rottenwood\UtopiaMudBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        parent::buildForm($builder, $options);

        // add your custom field
        $builder
            ->add('sex','choice',array('label'=>'Пол',
                                          'multiple'=>false,
                                          'choices'=>array(1=>'мужской', 2=>'женский'),
//                                          'attr'=>array('style'=>'width:300px', 'customattr'=>'customdata'),
                                          'data'=> 1
            ))
            ->add('race', 'entity', array(
                'class' => 'Rottenwood\UtopiaMudBundle\Entity\Race',
                'query_builder' => function(EntityRepository $repository) {
                        return $repository->createQueryBuilder('r')
                            ->select('r');
//                            ->where('r.id > 0');
                    }
                    ));
    }

    /**
     * Не менять
     * @return string
     */
    public function getName() {
        return 'utopiamud_user_registration';
    }
}
