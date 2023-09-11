<?php

declare(strict_types=1);

namespace App\Form\Book;

use App\DTO\Book\UpdateBookDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichFileType;

class BookUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название книги',
            ])
            ->add('isbn', TextType::class, [
                'label' => 'ISBN',
                'required' => false,
            ])
            ->add('year', NumberType::class, [
                'label' => 'Год издания',
                'required' => false,
            ])
            ->add('pageCount', NumberType::class, [
                'label' => 'Количество страниц',
                'required' => false,
            ])
            ->add('imageFile', VichFileType::class, [
                'required' => false,
                'label' => 'Обложка',
                'allow_delete' => false,
                'download_uri' => false,
            ])
            ->add('save', SubmitType::class, ['label' => 'Сохранить']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateBookDto::class,
        ]);
    }
}
