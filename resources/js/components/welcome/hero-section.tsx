import { HeroBackground } from '@/components/hero-background';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Eye, FileText, Phone, Search, Shield } from 'lucide-react';

interface HeroSectionProps {
    searchQuery: string;
    setSearchQuery: (query: string) => void;
    isVisible: boolean;
    t: (key: string) => string;
}

export function HeroSection({ searchQuery, setSearchQuery, isVisible, t }: HeroSectionProps) {
    const actionCards = [
        {
            icon: FileText,
            title: t('common:report_scammer'),
            description: t('common:submit_details_suspicious'),
            gradient: 'from-red-50 to-orange-50',
            iconBg: 'bg-red-600',
            borderColor: 'border-red-200',
            darkGradient: 'dark:from-red-950/20 dark:to-orange-950/20',
            darkBorder: 'dark:border-red-800',
        },
        {
            icon: Phone,
            title: t('common:check_number'),
            description: t('common:verify_phone_instantly'),
            gradient: 'from-blue-50 to-purple-50',
            iconBg: 'bg-blue-600',
            borderColor: 'border-blue-200',
            darkGradient: 'dark:from-blue-950/20 dark:to-purple-950/20',
            darkBorder: 'dark:border-blue-800',
        },
        {
            icon: Eye,
            title: t('common:verify_profile'),
            description: t('common:check_social_profiles'),
            gradient: 'from-green-50 to-teal-50',
            iconBg: 'bg-green-600',
            borderColor: 'border-green-200',
            darkGradient: 'dark:from-green-950/20 dark:to-teal-950/20',
            darkBorder: 'dark:border-green-800',
        },
    ];

    return (
        <section className="relative overflow-hidden pt-20 pb-32">
            <HeroBackground />
            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className={`transform text-center transition-all duration-1000 ${
                        isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                    }`}
                >
                    <div className="mb-8 inline-flex items-center rounded-full border border-red-200 bg-red-50 px-4 py-2 dark:border-red-800 dark:bg-red-950/30">
                        <Shield className="mr-2 h-4 w-4 text-red-600 dark:text-red-400" />
                        <span className="text-sm font-medium text-red-600 dark:text-red-400">
                            {t('common:protection_for_algeria')}
                        </span>
                    </div>

                    <h1 className="text-5xl leading-tight font-extrabold tracking-tight text-slate-900 md:text-6xl lg:text-7xl dark:text-white">
                        <span className="bg-gradient-to-r from-red-600 via-orange-600 to-yellow-600 bg-clip-text text-transparent">
                            {t('common:hero_title')}
                        </span>
                    </h1>

                    <p className="mx-auto mt-8 max-w-3xl text-xl leading-relaxed text-slate-600 dark:text-slate-300">
                        {t('common:hero_subtitle')}
                    </p>

                    {/* Search Bar */}
                    <div
                        className={`mx-auto mt-12 max-w-3xl transform transition-all delay-300 duration-1000 ${
                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                        }`}
                    >
                        <div className="relative">
                            <div className="absolute inset-0 rounded-2xl bg-gradient-to-r from-red-500/20 to-orange-500/20 blur-xl"></div>
                            <div className="relative rounded-2xl border border-slate-200 bg-white p-2 shadow-xl dark:border-slate-700 dark:bg-slate-800">
                                <div className="flex flex-col gap-3 sm:flex-row">
                                    <div className="relative flex-1">
                                        <div className="absolute top-1/2 left-4 -translate-y-1/2 transform text-slate-400">
                                            <Search className="h-5 w-5" />
                                        </div>
                                        <Input
                                            type="text"
                                            placeholder={t('common:search_placeholder')}
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            className="h-14 border-0 bg-transparent pl-12 text-lg focus-visible:ring-0"
                                        />
                                    </div>
                                    <Button
                                        size="lg"
                                        className="h-14 rounded-xl bg-red-600 px-8 font-semibold hover:bg-red-700 text-white"
                                    >
                                        {t('common:quick_search')}
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Action Cards */}
                    <div
                        className={`mx-auto mt-16 grid max-w-4xl transform grid-cols-1 gap-6 transition-all delay-500 duration-1000 md:grid-cols-3 ${
                            isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                        }`}
                    >
                        {actionCards.map((card, index) => (
                            <Card
                                key={index}
                                className={`group cursor-pointer ${card.borderColor} ${card.darkBorder} bg-gradient-to-br ${card.gradient} ${card.darkGradient} transition-all duration-300 hover:scale-105`}
                            >
                                <div className="p-6 text-center">
                                    <div
                                        className={`mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl ${card.iconBg} transition-transform group-hover:scale-110`}
                                    >
                                        <card.icon className="h-8 w-8 text-white" />
                                    </div>
                                    <h3 className="mb-2 text-lg font-bold text-slate-900 dark:text-white">{card.title}</h3>
                                    <p className="text-sm text-slate-600 dark:text-slate-400">{card.description}</p>
                                </div>
                            </Card>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}
