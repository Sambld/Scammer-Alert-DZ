import { useTranslation } from 'react-i18next';

export function useI18n() {
  const { t, i18n } = useTranslation();
  
  const isRTL = i18n.language === 'ar';
  const currentLanguage = i18n.language;
  
  const changeLanguage = (language: string) => {
    i18n.changeLanguage(language);
    
    // Update document direction for RTL languages
    document.documentElement.dir = language === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.lang = language;
  };

  return {
    t,
    i18n,
    isRTL,
    currentLanguage,
    changeLanguage,
  };
}
