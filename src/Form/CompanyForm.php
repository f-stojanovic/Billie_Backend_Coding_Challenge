<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'companyName',
                TextType::class,
                array('label' => 'Company Name', 'disabled' => false, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'email',
                TextType::class,
                array('label' => 'Email', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'address',
                TextType::class,
                array('label' => 'Address', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'phone',
                TextType::class,
                array('label' => 'Phone', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add('customerType', ChoiceType::class, [
                'choices' => [
                    'Creditor' => Company::TYPE_CREDITOR,
                    'Debtor'   => Company::TYPE_DEBTOR
                ],
                'attr' => ['class' => 'form-control']
            ])
            ->add(
                'debtLimit',
                TextType::class,
                array('label' => 'Debt Limit', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'save',
                SubmitType::class,
                array('label' => 'Save',
                    'attr' => [
                        'class' => 'btn btn-primary pull-left action-save'
                    ]
                )
            );
    }

    /**
     * @param OptionsResolver $resolver
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Company::class,
        ));
    }
}
