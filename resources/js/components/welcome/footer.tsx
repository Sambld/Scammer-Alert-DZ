export function Footer({ t }: { t: (key: string) => string }) {
    return (
        <footer className="bg-slate-900 py-12">
            <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div className="text-center">
                    <div className="mb-4 flex items-center justify-center space-x-3">
                        <div className="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-orange-500 font-bold text-white">
                            E
                        </div>
                        <span className="text-lg font-bold text-white">ScammerAlertDZ</span>
                    </div>
                    <p className="text-slate-400">{t('common:footer_tagline')}</p>
                </div>
            </div>
        </footer>
    );
}
