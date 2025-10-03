import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Clock, Shield, Target } from 'lucide-react';
import { type LucideIcon } from 'lucide-react';

interface Step {
    title: string;
    description: string;
    icon: LucideIcon;
    color: string;
}

interface HowItWorksSectionProps {
    steps: Step[];
    isVisible: boolean;
    t: (key: string) => string;
}

export function HowItWorksSection({ steps, isVisible, t }: HowItWorksSectionProps) {
    return (
        <section className="relative overflow-hidden bg-white py-24 dark:bg-slate-800">
            <div className="absolute inset-0">
                <div className="absolute inset-0 bg-gradient-to-br from-orange-50/50 via-transparent to-red-50/50 dark:from-orange-950/10 dark:via-transparent dark:to-red-950/10"></div>
                <div className="absolute inset-0 opacity-5 md:hidden">
                    <div
                        className="absolute inset-0"
                        style={{
                            backgroundImage: `url("data:image/svg+xml,%3csvg width='40' height='40' xmlns='http://www.w3.org/2000/svg'%3e%3cdefs%3e%3cpattern id='grid' width='40' height='40' patternUnits='userSpaceOnUse'%3e%3cpath d='m 40 0 l 0 40 l -40 0 l 0 -40 z' fill='none' stroke='%23000000' stroke-width='1'/%3e%3c/pattern%3e%3c/defs%3e%3crect width='100%25' height='100%25' fill='url(%23grid)' /%3e%3c/svg%3e")`,
                        }}
                    ></div>
                </div>
            </div>

            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="mb-20 text-center">
                    <div className="mb-6 inline-flex items-center rounded-full border border-orange-200 bg-orange-50 px-4 py-2 dark:border-orange-800 dark:bg-orange-950/30">
                        <Target className="mr-2 h-4 w-4 text-orange-600 dark:text-orange-400" />
                        <span className="text-sm font-medium text-orange-600 dark:text-orange-400">
                            {t('common:how_it_works_badge')}
                        </span>
                    </div>
                    <h2 className="mb-6 text-3xl font-bold text-slate-900 md:text-4xl dark:text-white">
                        {t('common:how_it_works')}
                    </h2>
                    <p className="mx-auto max-w-3xl text-lg text-slate-600 md:text-xl dark:text-slate-400">
                        {t('common:three_simple_steps')}
                    </p>
                </div>

                <div className="relative">
                    {/* Timeline lines */}
                    <div className="absolute left-1/2 hidden h-full w-1 -translate-x-1/2 transform bg-gradient-to-b from-red-200 via-orange-200 to-yellow-200 md:block dark:from-red-800 dark:via-orange-800 dark:to-yellow-800"></div>
                    <div className="absolute top-0 left-8 h-full w-0.5 bg-gradient-to-b from-red-200 via-orange-200 to-yellow-200 md:hidden dark:from-red-800 dark:via-orange-800 dark:to-yellow-800"></div>

                    <div className="relative space-y-12 md:space-y-16">
                        {steps.map((step, index) => (
                            <div
                                key={index}
                                className={`relative flex flex-col items-start md:flex-row md:items-center ${
                                    index % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse'
                                } transform transition-all duration-700 ${
                                    isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                                }`}
                                style={{ transitionDelay: `${1200 + index * 200}ms` }}
                            >
                                {/* Mobile Layout */}
                                <div className="flex w-full items-start space-x-4 md:hidden">
                                    <div className="flex-shrink-0">
                                        <div className="relative">
                                            <div
                                                className={`h-16 w-16 rounded-2xl bg-gradient-to-r ${step.color} flex items-center justify-center text-white shadow-lg ring-4 ring-white dark:ring-slate-800`}
                                            >
                                                <step.icon className="h-8 w-8" />
                                            </div>
                                            <div className="absolute -top-2 -right-2 flex h-6 w-6 items-center justify-center rounded-full border-2 border-slate-200 bg-white text-xs font-bold text-slate-700 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                                {index + 1}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="flex-1 pb-8">
                                        <Card className="border-l-4 border-l-orange-500 bg-gradient-to-br from-white to-slate-50 p-6 shadow-lg transition-all duration-300 hover:shadow-xl dark:from-slate-800 dark:to-slate-900">
                                            <div className="mb-3 flex items-center justify-between">
                                                <h3 className="text-xl font-bold text-slate-900 dark:text-white">{step.title}</h3>
                                                <div className="flex items-center space-x-1">
                                                    {[...Array(steps.length)].map((_, i) => (
                                                        <div
                                                            key={i}
                                                            className={`h-2 w-2 rounded-full transition-colors ${
                                                                i <= index ? 'bg-orange-500' : 'bg-slate-300 dark:bg-slate-600'
                                                            }`}
                                                        ></div>
                                                    ))}
                                                </div>
                                            </div>
                                            <p className="mb-4 leading-relaxed text-slate-600 dark:text-slate-400">
                                                {step.description}
                                            </p>
                                            <div className="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400">
                                                <div className="flex items-center">
                                                    <Clock className="mr-2 h-4 w-4" />
                                                    <span>{t('common:step')} {index + 1} {t('common:of')} {steps.length}</span>
                                                </div>
                                            </div>
                                        </Card>
                                    </div>
                                </div>

                                {/* Desktop Layout */}
                                <div className="hidden w-full md:block">
                                    <div className="relative flex min-h-[200px] items-center">
                                        <div className={`w-5/12 ${index % 2 === 0 ? 'pr-8' : ''}`}>
                                            {index % 2 === 0 && (
                                                <Card className="border-l-4 border-l-orange-500 bg-gradient-to-br from-white to-slate-50 p-8 shadow-xl transition-all duration-300 hover:shadow-2xl dark:from-slate-800 dark:to-slate-900">
                                                    <div className="flex items-start space-x-4">
                                                        <div
                                                            className={`flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-r ${step.color} text-xl font-bold text-white`}
                                                        >
                                                            {index + 1}
                                                        </div>
                                                        <div>
                                                            <h3 className="mb-3 text-2xl font-bold text-slate-900 dark:text-white">
                                                                {step.title}
                                                            </h3>
                                                            <p className="leading-relaxed text-slate-600 dark:text-slate-400">
                                                                {step.description}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </Card>
                                            )}
                                        </div>

                                        <div className="relative z-10 flex w-2/12 justify-center">
                                            <div className="flex h-20 w-20 items-center justify-center rounded-full border-4 border-slate-200 bg-white shadow-lg dark:border-slate-600 dark:bg-slate-800">
                                                <div
                                                    className={`h-12 w-12 rounded-full bg-gradient-to-r ${step.color} flex items-center justify-center text-white`}
                                                >
                                                    <step.icon className="h-6 w-6" />
                                                </div>
                                            </div>
                                        </div>

                                        <div className={`w-5/12 ${index % 2 === 1 ? 'pl-8' : ''}`}>
                                            {index % 2 === 1 && (
                                                <Card className="border-r-4 border-r-orange-500 bg-gradient-to-br from-white to-slate-50 p-8 shadow-xl transition-all duration-300 hover:shadow-2xl dark:from-slate-800 dark:to-slate-900">
                                                    <div className="flex items-start space-x-4">
                                                        <div
                                                            className={`flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-r ${step.color} text-xl font-bold text-white`}
                                                        >
                                                            {index + 1}
                                                        </div>
                                                        <div>
                                                            <h3 className="mb-3 text-2xl font-bold text-slate-900 dark:text-white">
                                                                {step.title}
                                                            </h3>
                                                            <p className="leading-relaxed text-slate-600 dark:text-slate-400">
                                                                {step.description}
                                                            </p>
                                                        </div>
                                                    </div>
                                                </Card>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                <div className="mt-16 text-center">
                    <Button
                        size="lg"
                        className="bg-gradient-to-r from-orange-500 to-red-500 px-8 text-white hover:from-orange-600 hover:to-red-600"
                    >
                        <Shield className="mr-2 h-5 w-5" />
                        {t('common:start_protecting_now')}
                    </Button>
                </div>
            </div>
        </section>
    );
}
