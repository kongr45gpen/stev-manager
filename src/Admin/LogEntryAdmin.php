<?php

namespace App\Admin;


use App\Entity\Instance;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class LogEntryAdmin extends AbstractAdmin
{
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('id')
            ->add('action')
            ->add('logged_at')
            ->add('username')
            ->add('object_class')
            ->add('object_id')
            ->add('version')
//            ->add('data')

            ->add('_action', null, [
                'actions' => [
                    'show' => [],
//                    'edit' => [],
//                    'delete' => [],
                ],
                'row_align' => 'right',
                'header_style' => 'width: 15%'
            ])

        ;
    }

    public function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('Details', ['class' => 'col-md-4'])
                ->add('id')
                ->add('action')
                ->add('logged_at')
                ->add('username')
                ->add('object_class')
                ->add('object_id')
                ->add('version')
            ->end()
            ->with('Data', ['class' => 'col-md-8'])
                ->add('data')
            ->end()
        ;
    }
}
