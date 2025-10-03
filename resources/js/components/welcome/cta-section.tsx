import { Button } from '@/components/ui/button';
import { Rocket } from 'lucide-react';

interface CTASectionProps {
    isVisible: boolean;
    t: (key: string) => string;
}

export function CTASection({ isVisible, t }: CTASectionProps) {
    return (
        <section className="relative overflow-hidden py-16 sm:py-20 md:py-24">
            <div className="absolute inset-0 bg-gradient-to-r from-red-600 via-orange-500 to-yellow-500"></div>
            <div className="absolute inset-0 bg-gradient-to-br from-black/20 via-transparent to-black/20"></div>

            <div className="absolute inset-0">
                {[...Array(5)].map((_, i) => (
                    <div
                        key={i}
                        className="animate-float absolute h-32 w-32 rounded-full bg-white/10 blur-xl"
                        style={{
                            left: `${15 + i * 20}%`,
                            top: `${20 + (i % 2) * 40}%`,
                            animationDelay: `${i * 0.8}s`,
                            animationDuration: `${4 + i * 0.5}s`,
                        }}
                    />
                ))}
            </div>

            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div
                    className={`transform text-center transition-all duration-1000 ${
                        isVisible ? 'translate-y-0 opacity-100' : 'translate-y-10 opacity-0'
                    }`}
                    style={{ transitionDelay: '2200ms' }}
                >
                    <div className="mb-8 inline-flex items-center rounded-full border border-white/30 bg-white/20 px-6 py-3 backdrop-blur-sm">
                        <Rocket className="mr-2 h-5 w-5 text-white" />
                        <span className="font-medium text-white">{t('common:join_the_movement')}</span>
                    </div>

                    <h2 className="mb-8 text-4xl leading-tight font-bold text-white md:text-5xl lg:text-6xl">
                        {t('common:cta_title')}
                    </h2>

                    <p className="mx-auto mb-12 max-w-3xl text-xl leading-relaxed text-white/90">{t('common:cta_subtitle')}</p>

                    <div className="flex flex-col items-center justify-center gap-6 sm:flex-row">
                        <Button
                            size="lg"
                            className="rounded-2xl bg-white px-12 py-4 font-semibold text-slate-900 shadow-xl transition-all duration-300 hover:scale-105 hover:bg-slate-100 hover:shadow-2xl"
                        >
                            <span className="text-lg">{t('common:join_community')}</span>
                        </Button>

                        <Button
                            size="lg"
                            variant="outline"
                            className="rounded-2xl border-2 border-white/30 px-12 py-4 font-semibold text-white backdrop-blur-sm transition-all duration-300 hover:scale-105 hover:bg-white/10"
                        >
                            <span className="text-lg">{t('common:learn_more')}</span>
                        </Button>
                    </div>

                    <div className="mx-auto mt-16 grid max-w-4xl grid-cols-1 gap-8 md:grid-cols-3">
                        <div className="text-center">
                            <div className="mb-2 text-3xl font-bold text-white">{t('common:community_protection_24_7')}</div>
                            <div className="text-white/80">{t('common:community_protection')}</div>
                        </div>
                        <div className="text-center">
                            <div className="mb-2 text-3xl font-bold text-white">{t('common:free_platform_100')}</div>
                            <div className="text-white/80">{t('common:free_platform')}</div>
                        </div>
                        <div className="text-center">
                            <div className="mb-2 text-3xl font-bold text-white">{t('common:protected_users_5000')}</div>
                            <div className="text-white/80">{t('common:protected_users')}</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    );
}
