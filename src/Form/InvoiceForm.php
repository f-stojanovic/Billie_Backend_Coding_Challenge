<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Repository\CompanyRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvoiceForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debtorCompanyId', EntityType::class,[
                'class' => Company::class,
                'choice_label' => 'companyName',
                'label' => 'Debtor Company',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (CompanyRepository $cr) {
                    return $cr->createQueryBuilder('d')
                        ->andWhere('d.customerType = :customerType')
                        ->setParameter('customerType', Company::TYPE_DEBTOR);
                }])
            ->add('creditorCompanyId', EntityType::class,[
                'class' => Company::class,
                'choice_label' => 'companyName',
                'label' => 'Creditor Company',
                'attr' => ['class' => 'form-control'],
                'query_builder' => function (CompanyRepository $cr) {
                    return $cr->createQueryBuilder('d')
                        ->andWhere('d.customerType = :customerType')
                        ->setParameter('customerType', Company::TYPE_CREDITOR);
                }])
            ->add(
                'service',
                TextType::class,
                array('label' => 'Service', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'quantity',
                TextType::class,
                array('label' => 'Quantity', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'cost',
                TextType::class,
                array('label' => 'Cost', 'required' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add(
                'currency',
                TextType::class,
                array('label' => 'Currency', 'disabled' => true, 'attr' => ['class' => 'form-control'])
            )
            ->add('statusType', ChoiceType::class, [
                'choices' => [
                    'Open'   => Invoice::STATUS_OPEN,
                    'Closed' => Invoice::STATUS_CLOSED
                ],
                'attr' => ['class' => 'form-control']
            ])
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
            'data_class' => Invoice::class,
        ));
    }
}
