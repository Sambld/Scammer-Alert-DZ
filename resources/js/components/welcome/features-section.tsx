import { Card } from '@/components/ui/card';
import { Star } from 'lucide-react';
import { type LucideIcon } from 'lucide-react';

interface Feature {
    title: string;
    description: string;
    icon: LucideIcon;
}

interface FeaturesSectionProps {
    features: Feature[];
    isVisible: boolean;
    t: (key: string) => string;
}

export function FeaturesSection({ features, isVisible, t }: FeaturesSectionProps) {
    const getGridClasses = (index: number) => {
        if (index === 0) return 'col-span-1 sm:col-span-2 lg:col-span-6 lg:row-span-2';
        return 'col-span-1 sm:col-span-2 lg:col-span-6';
    };

    return (
        <section className="relative overflow-hidden bg-gradient-to-br from-slate-50 via-white to-orange-50 py-16 sm:py-20 md:py-24 dark:from-slate-900 dark:via-slate-800 dark:to-orange-950/20">
            <div className="absolute inset-0">
                <div className="absolute top-20 left-10 h-32 w-32 rounded-full bg-orange-200/20 blur-3xl dark:bg-orange-800/20"></div>
                <div className="absolute right-10 bottom-20 h-40 w-40 rounded-full bg-red-200/20 blur-3xl dark:bg-red-800/20"></div>
            </div>

            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-12 sm:mb-16 lg:mb-20 text-center">
                    <div className="mb-6 inline-flex items-center rounded-full border border-orange-200 bg-orange-50 px-4 py-2 dark:border-orange-800 dark:bg-orange-950/30">
                        <Star className="mr-2 h-4 w-4 text-orange-600 dark:text-orange-400" />
                        <span className="text-sm font-medium text-orange-600 dark:text-orange-400">
                            {t('common:why_choose_us_badge')}
                        </span>
                    </div>
                    <h2 className="mb-6 text-3xl font-bold text-slate-900 md:text-4xl lg:text-5xl dark:text-white">
                        {t('common:why_choose_us')}
                    </h2>
                    <p className="mx-auto max-w-3xl text-lg text-slate-600 md:text-xl dark:text-slate-400">
                        {t('common:built_by_community')}
                    </p>
                </div>

                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-12 lg:gap-6">
                    {features.map((feature, index) => (
                        <Card
                            key={index}
                            className={`group relative overflow-hidden border-0 bg-white/90 shadow-lg backdrop-blur-sm transition-all duration-700 hover:scale-[1.02] hover:shadow-2xl dark:bg-slate-800/90 ${getGridClasses(index)} ${
                                isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                            }`}
                            style={{ transitionDelay: `${1800 + index * 150}ms` }}
                        >
                            <div className="absolute inset-0 bg-gradient-to-br from-red-500/5 to-orange-500/5 opacity-0 transition-opacity duration-500 group-hover:opacity-100"></div>

                            {index === 0 && (
                                <div className="absolute inset-0 opacity-5">
                                    <div
                                        className="absolute inset-0"
                                        style={{
                                            backgroundImage: `url("data:image/svg+xml,%3csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='hero-grid' width='60' height='60' patternUnits='userSpaceOnUse'%3e%3cpath d='m 60 0 l 0 60 l -60 0 l 0 -60 z' fill='none' stroke='%23f97316' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23hero-grid)' /%3e%3c/svg%3e")`,
                                        }}
                                    ></div>
                                </div>
                            )}

                            <div className={`relative ${index === 0 ? 'p-8 lg:p-12' : 'p-6 lg:p-8'}`}>
                                <div
                                    className={`flex ${index === 0 ? 'flex-col items-center text-center lg:items-start lg:text-left' : 'items-center'} ${index === 0 ? 'mb-8' : 'mb-6'} space-x-0 ${index === 0 ? 'lg:space-x-0' : 'space-x-4'}`}
                                >
                                    <div
                                        className={`${index === 0 ? 'mb-4 h-20 w-20 lg:mb-6 lg:h-24 lg:w-24' : 'h-12 w-12 lg:h-14 lg:w-14'} flex flex-shrink-0 items-center justify-center rounded-2xl bg-red-100 transition-transform duration-300 group-hover:scale-110 dark:bg-red-900/30`}
                                    >
                                        <feature.icon
                                            className={`${index === 0 ? 'h-10 w-10 lg:h-12 lg:w-12' : 'h-6 w-6 lg:h-7 lg:w-7'} text-red-600 dark:text-red-400`}
                                        />
                                    </div>
                                    <div className={index === 0 ? '' : 'flex-1'}>
                                        <h3
                                            className={`font-bold text-slate-900 dark:text-white ${index === 0 ? 'mb-4 text-2xl lg:text-3xl' : 'text-lg lg:text-xl'}`}
                                        >
                                            {feature.title}
                                        </h3>
                                        {index === 0 && (
                                            <div className="mb-4 flex items-center justify-center space-x-1 lg:justify-start">
                                                {[...Array(5)].map((_, i) => (
                                                    <Star key={i} className="h-5 w-5 fill-current text-yellow-400" />
                                                ))}
                                                <span className="ml-2 text-sm text-slate-600 dark:text-slate-400">
                                                    {t('common:trusted_by_thousands')}
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </div>

                                <p
                                    className={`leading-relaxed text-slate-600 dark:text-slate-400 ${index === 0 ? 'text-center text-base lg:text-left lg:text-lg' : 'text-sm lg:text-base'}`}
                                >
                                    {feature.description}
                                </p>

                                {index === 0 && (
                                    <div className="mt-8 border-t border-slate-200 pt-6 dark:border-slate-700">
                                        <div className="flex flex-wrap justify-center gap-2 lg:justify-start">
                                            <span className="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                                {t('common:community_driven_badge')}
                                            </span>
                                            <span className="rounded-full bg-orange-100 px-3 py-1 text-xs font-medium text-orange-700 dark:bg-orange-900/30 dark:text-orange-300">
                                                {t('common:free_to_use_badge')}
                                            </span>
                                            <span className="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                {t('common:realtime_updates_badge')}
                                            </span>
                                        </div>
                                    </div>
                                )}

                                <div className="absolute right-4 bottom-4 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                    <div className="h-2 w-2 animate-pulse rounded-full bg-orange-500"></div>
                                </div>
                            </div>
                        </Card>
                    ))}
                </div>
            </div>
        </section>
    );
}
