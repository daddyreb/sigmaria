<?php

namespace App\Form\Ancestry;

use App\Entity\Ancestry\AncestralFeature;
use App\Entity\Ancestry\AncestralHitPoints;
use App\Entity\Ancestry\Ancestry;
use App\Entity\Ancestry\Heritage;
use App\Entity\Core\Ability;
use App\Entity\Core\Attribute;
use App\Entity\Core\AttributeCategory;
use App\Entity\Core\Feat;
use App\Entity\Core\MoveSpeed;
use App\Entity\Core\Rarity;
use App\Entity\Core\Size;
use App\Form\Core\BaseEntityType;
use Doctrine\ORM\EntityRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HeritageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('shortDescription', CKEditorType::class, [
                'label' => 'Krótki opis',
                'required' => false,
                'config' => BaseEntityType::DEFAULT_CKEDITOR_CONFIG,
            ])
            ->add('valueAdjustment', IntegerType::class, [
                'label' => 'Korekta wartości',
                'required' => false,
            ])
            ->add('rarity', EntityType::class, [
                'label' => 'Rzadkość',
                'class' => Rarity::class
            ])
            ->add('ancestry', EntityType::class, [
                'class' => Ancestry::class,
                'label' => 'Rasa',
            ])
            ->add('hitPoints', EntityType::class, [
                'class' => AncestralHitPoints::class,
                'label' => 'Punkty zdrowia',
                'required' => false,
                'placeholder' => 'Wybierz',
            ])
            ->add('size', EntityType::class, [
                'class' => Size::class,
                'label' => 'Rozmiar',
                'required' => false,
                'placeholder' => 'Wybierz',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('s')
                        ->andWhere('s.isPlayerCharacterSize = true');
                },
            ])
            ->add('speed', EntityType::class, [
                'class' => MoveSpeed::class,
                'label' => 'Prędkość ruchu',
                'required' => false,
                'placeholder' => 'Wybierz',
            ])
            ->add('abilityBoosts', EntityType::class, [
                'class' => Ability::class,
                'label' => 'Premie do cech',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('attributes', EntityType::class, [
                'class' => Attribute::class,
                'label' => 'Atrybuty',
                'multiple' => true,
                'group_by' => function (Attribute $attribute) {
                    return $attribute->getCategory()->getName();
                },
                'attr' => [
                    'class' => 'js-example-basic-multiple',
                ]
            ])
            ->add('ancestralFeatures', EntityType::class, [
                'class' => AncestralFeature::class,
                'label' => 'Zdolności dziedzictwa',
                'multiple' => true,
                'attr' => [
                    'class' => 'js-example-basic-multiple',
                ]
            ])
            ->add('feats', EntityType::class, [
                'class' => Feat::class,
                'label' => 'Atuty dziedzictwa',
                'multiple' => true,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('f')
                        ->innerJoin('f.attributes', 'a')
                        ->innerJoin('a.category', 'c')
                        ->andWhere('c.handle IN (:categories)')
                        ->setParameter(
                            'categories',
                            [
                                AttributeCategory::ATTRIBUTE_CATEGORY_HERITAGE,
                                AttributeCategory::ATTRIBUTE_CATEGORY_ANCESTRAL,
                            ]
                        );
                },
                'attr' => [
                    'class' => 'js-example-basic-multiple',
                ]
            ]);
    }

    public function getParent()
    {
        return BaseEntityType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Heritage::class,
        ]);
    }
}