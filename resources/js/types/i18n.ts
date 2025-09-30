export interface LocaleData {
  current: string;
  direction: 'ltr' | 'rtl';
  supported: string[];
}

export interface TranslationNamespaces {
  common: {
    welcome: string;
    home: string;
    dashboard: string;
    profile: string;
    settings: string;
    logout: string;
    login: string;
    register: string;
    email: string;
    password: string;
    confirm_password: string;
    remember_me: string;
    forgot_password: string;
    name: string;
    save: string;
    cancel: string;
    edit: string;
    delete: string;
    search: string;
    filter: string;
    sort: string;
    actions: string;
    loading: string;
    no_data: string;
    error: string;
    success: string;
    warning: string;
    info: string;
    yes: string;
    no: string;
    close: string;
    submit: string;
    reset: string;
    back: string;
    next: string;
    previous: string;
    page_of: string;
    showing_results: string;
  };
  scam_alert: {
    title: string;
    subtitle: string;
    report_scam: string;
    view_reports: string;
    latest_alerts: string;
    scam_types: string;
    prevention_tips: string;
  };
}
