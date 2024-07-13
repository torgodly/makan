<?php

namespace App\Filament\Reports;

use EightyNine\Reports\ReportsPlugin;
use Filament\Navigation\NavigationItem;
use Filament\Panel;

class CustomReportsPlugin extends ReportsPlugin
{
    public function boot(Panel $panel): void
    {

        if (!reports()->getUseReportListPage()) {
            $panel->navigationItems(collect(reports()->getReports())->map(function ($report) {
                $report = app($report);
                $title = $report->getTitle();
                if ($title == 'User Report') {
                    return NavigationItem::make($report->getTitle())
                        ->label('تقرير الموظف')
                        ->url(function () use ($report) {
                            return $report->getUrl();
                        })
                        ->group(reports()->getNavigationGroup() ?? __('التقارير'));
                } else {
                    return NavigationItem::make($report->getTitle())
                        ->label('تقرير الأرباح')
                        ->url(function () use ($report) {
                            return $report->getUrl();
                        })
                        ->group(reports()->getNavigationGroup() ?? __('التقارير'));
                }

            })->toArray());
        }
    }
}
