<?php

namespace KPhoen\GaufretteExtrasBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;


/**
 * Extends the File type, upload an image but show a version of the currently uploaded image.
 *
 * @example:
 *  $builder->add('avatar', 'image', array(
 *      'gaufrette'             => 'avatars',
 *      'image_path'            => 'avatar', // because there is a getAvatar() method in the data class
 *
 *      'image_alt'             => 'Avatar',
 *      'image_width'           => '100px',
 *      'image_height'          => '100px',
 *      'no_image_placeholder'  => 'noImage.jpg',
 *  ));
 */
class ImageType extends AbstractType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('gaufrette', 'image_path'));
        $resolver->setDefaults(array(
            'image_alt'             => '',
            'image_width'           => null,
            'image_height'          => null,
            'no_image_placeholder'  => null,
        ));
    }

    /**
     * Pass the image url to the view
     *
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $parentData = $form->getParent()->getData();

        if ($parentData !== null) {
            $accessor = PropertyAccess::getPropertyAccessor();

            // set an "image_url" variable that will be available when rendering this field
            $view->vars['image_url'] = $accessor->getValue($parentData, $options['image_path']);
        } else if ($options['no_image_placeholder'] !== null) {
            $view->vars['image_url'] = $options['no_image_placeholder'];
        }

        $view->vars['gaufrette'] = $options['gaufrette'];
        $view->vars['image_alt'] = $options['image_alt'];
        $view->vars['image_width'] = $options['image_width'];
        $view->vars['image_height'] = $options['image_height'];
    }

    public function getParent()
    {
        return 'file';
    }

    public function getName()
    {
        return 'image';
    }
}
