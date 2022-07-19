<?php
/**
 * This file is part of the SharedProjectTimesheetsBundle for Kimai 2.
 * All rights reserved by Fabian Vetter (https://vettersolutions.de).
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace KimaiPlugin\SharedProjectTimesheetsBundle\Form;

use App\Form\Type\ProjectType;
use KimaiPlugin\SharedProjectTimesheetsBundle\Entity\SharedProjectTimesheet;
use KimaiPlugin\SharedProjectTimesheetsBundle\Model\RecordMergeMode;
use KimaiPlugin\SharedProjectTimesheetsBundle\Service\ManageService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SharedProjectFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $mergeRecordTypes = array_flip(RecordMergeMode::getModes());

        $builder
            ->add('project', ProjectType::class, [
                'required' => true,
            ])
            ->add('recordMergeMode', ChoiceType::class, [
                'label' => 'shared_project_timesheets.manage.form.record_merge_mode',
                'required' => true,
                'choices' => $mergeRecordTypes,
            ])
            ->add('password', PasswordType::class, [
                'label' => 'label.password',
                'required' => false,
                'always_empty' => false,
                'mapped' => false,
                'help' => 'shared_project_timesheets.manage.form.password_hint',
            ])
            ->add(
                $builder
                    ->create('tableOptions', FormType::class, [
                        'label' => 'shared_project_timesheets.manage.form.table_options',
                        'inherit_data' => true,
                        'required' => false,
                    ])
                    ->add('entryUserVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.entry_user_visible',
                        'required' => false,
                    ])
                    ->add('entryRateVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.entry_rate_visible',
                        'required' => false,
                    ])
            )
            ->add(
                $builder
                    ->create('chartOptions', FormType::class, [
                        'label' => 'shared_project_timesheets.manage.form.chart_options',
                        'inherit_data' => true,
                        'required' => false,
                    ])
                    ->add('annualChartVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.annual_chart_visible',
                        'required' => false,
                    ])
                    ->add('projectBudgetVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.project_budget_visible',
                        'required' => false,
                    ])
                    ->add('projectTimeBudgetVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.project_time_budget_visible',
                        'required' => false,
                    ])
                    ->add('activityVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.activity_visible',
                        'required' => false,
                    ])
                    ->add('monthlyChartVisible', CheckboxType::class, [
                        'label' => 'shared_project_timesheets.manage.form.monthly_chart_visible',
                        'required' => false,
                    ])
            )
            ->add('save', SubmitType::class, [
                'label' => 'action.save',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SharedProjectTimesheet::class,
        ]);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (!empty($form->get('password')->getData())) {
            $view['password']->vars['value'] = ManageService::PASSWORD_DO_NOT_CHANGE_VALUE;
        }
    }

}
