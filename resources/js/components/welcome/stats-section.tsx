import { AnimatedCounter } from '@/components/animations';
import { Card } from '@/components/ui/card';
import { TrendingUp } from 'lucide-react';
import { type LucideIcon } from 'lucide-react';

interface Stat {
    label: string;
    value: string;
    icon: LucideIcon;
}

interface StatsSectionProps {
    stats: Stat[];
    isVisible: boolean;
    t: (key: string) => string;
}

export function StatsSection({ stats, isVisible, t }: StatsSectionProps) {
    return (
        <section className="relative bg-gradient-to-br from-slate-50 via-white to-blue-50 py-24 dark:from-slate-900 dark:via-slate-800 dark:to-blue-950/20">
            <div className="absolute inset-0">
                <div className="absolute top-0 left-0 h-full w-full">
                    {[...Array(3)].map((_, i) => (
                        <div
                            key={i}
                            className="absolute h-64 w-64 animate-pulse rounded-full bg-gradient-to-r from-blue-200/20 to-purple-200/20 blur-3xl"
                            style={{
                                left: `${20 + i * 30}%`,
                                top: `${10 + i * 20}%`,
                                animationDelay: `${i * 2}s`,
                                animationDuration: `${4 + i}s`,
                            }}
                        />
                    ))}
                </div>
            </div>

            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-16 text-center">
                    <div className="mb-6 inline-flex items-center rounded-full border border-blue-200 bg-blue-50 px-4 py-2 dark:border-blue-800 dark:bg-blue-950/30">
                        <TrendingUp className="mr-2 h-4 w-4 text-blue-600 dark:text-blue-400" />
                        <span className="text-sm font-medium text-blue-600 dark:text-blue-400">
                            {t('common:platform_impact')}
                        </span>
                    </div>
                    <h2 className="mb-4 text-3xl font-bold text-slate-900 md:text-4xl dark:text-white">
                        {t('common:real_numbers_protection')}
                    </h2>
                    <p className="mx-auto max-w-3xl text-lg text-slate-600 md:text-xl dark:text-slate-400">
                        {t('common:community_difference_desc')}
                    </p>
                </div>

                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                    {stats.map((stat, index) => (
                        <Card
                            key={index}
                            className={`group relative overflow-hidden border-0 bg-white/80 shadow-lg backdrop-blur-sm transition-all duration-700 hover:scale-105 hover:shadow-2xl dark:bg-slate-800/80 ${
                                isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                            }`}
                            style={{ transitionDelay: `${800 + index * 100}ms` }}
                        >
                            <div className="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-purple-500/5 opacity-0 transition-opacity group-hover:opacity-100"></div>
                            <div className="absolute top-4 right-4 h-20 w-20 rounded-full bg-gradient-to-br from-blue-100/50 to-purple-100/50 opacity-0 blur-lg transition-opacity group-hover:opacity-100 dark:from-blue-900/30 dark:to-purple-900/30"></div>

                            <div className="relative p-6 text-center lg:p-8">
                                <div className="mb-4 inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-100 to-purple-100 transition-transform group-hover:scale-110 lg:h-20 lg:w-20 dark:from-blue-900/50 dark:to-purple-900/50">
                                    <stat.icon className="h-8 w-8 text-blue-600 lg:h-10 lg:w-10 dark:text-blue-400" />
                                </div>

                                <div className="mb-2 text-3xl font-bold tracking-tight text-slate-900 lg:text-4xl dark:text-white">
                                    <AnimatedCounter value={stat.value} />
                                </div>

                                <div className="text-sm leading-relaxed font-medium text-slate-600 lg:text-base dark:text-slate-400">
                                    {stat.label}
                                </div>

                                <div className="mt-4 sm:hidden">
                                    <div className="h-1 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-700">
                                        <div
                                            className="h-full rounded-full bg-gradient-to-r from-blue-500 to-purple-500 transition-all duration-1000"
                                            style={{
                                                width: `${Math.min(
                                                    100,
                                                    (parseInt(stat.value.replace(/[,+]/g, '')) /
                                                        (index === 0 ? 50000 : index === 1 ? 10000 : index === 2 ? 5000 : 1000)) *
                                                        100
                                                )}%`,
                                                transitionDelay: `${1000 + index * 200}ms`,
                                            }}
                                        ></div>
                                    </div>
                                    <div className="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                        {index === 0
                                            ? t('common:growing_daily')
                                            : index === 1
                                              ? t('common:reports_verified')
                                              : index === 2
                                                ? t('common:active_users')
                                                : t('common:response_time')}
                                    </div>
                                </div>

                                <div className="absolute right-3 bottom-3 hidden h-2 w-2 animate-pulse rounded-full bg-gradient-to-r from-blue-500 to-purple-500 opacity-0 transition-opacity group-hover:opacity-100 sm:block"></div>
                            </div>
                        </Card>
                    ))}
                </div>

                <div className="mt-12 text-center">
                    <div className="mb-8 sm:hidden">
                        <p className="mb-4 text-sm text-slate-500 dark:text-slate-400">
                            {t('common:updated_realtime_community')}
                        </p>
                        <div className="flex items-center justify-center space-x-6 text-xs text-slate-400 dark:text-slate-500">
                            <div className="flex items-center">
                                <div className="mr-2 h-2 w-2 animate-pulse rounded-full bg-green-500"></div>
                                {t('common:live_data')}
                            </div>
                            <div className="flex items-center">
                                <div className="mr-2 h-2 w-2 rounded-full bg-blue-500"></div>
                                {t('common:verified')}
                            </div>
                            <div className="flex items-center">
                                <div className="mr-2 h-2 w-2 rounded-full bg-purple-500"></div>
                                {t('common:trusted')}
                            </div>
                        </div>
                    </div>
                    <div className="hidden sm:block">
                        <p className="text-sm text-slate-500 dark:text-slate-400">{t('common:realtime_stats_powered')}</p>
                    </div>
                </div>
            </div>
        </section>
    );
}
