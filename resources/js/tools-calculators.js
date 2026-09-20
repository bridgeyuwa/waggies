const dogSizes = [
    { id: 'small', label: 'Small', description: 'e.g. Chihuahua, Pug, Yorkshire Terrier', kgRange: 'up to ~9 kg', lbRange: 'up to ~20 lbs' },
    { id: 'medium', label: 'Medium', description: 'e.g. Beagle, Corgi, Border Collie', kgRange: '9–23 kg', lbRange: '21–50 lbs' },
    { id: 'large', label: 'Large', description: 'e.g. Golden Retriever, German Shepherd', kgRange: '23–45 kg', lbRange: '51–100 lbs' },
    { id: 'giant', label: 'Giant', description: 'e.g. Great Dane, Mastiff, St. Bernard', kgRange: '45+ kg', lbRange: '100+ lbs' },
];

const petAgeCalculator = () => ({
    petType: 'dog',
    dogSize: 'medium',
    years: 0,
    months: 0,
    dogSizes,
    maxYears() {
        return this.petType === 'dog' ? 30 : 40;
    },
    yearsOptions() {
        return Array.from({ length: this.maxYears() + 1 }, (_, index) => index);
    },
    monthsOptions() {
        return Array.from({ length: 12 }, (_, index) => index);
    },
    changePetType(type) {
        this.petType = type;
        this.years = Math.min(Number(this.years), type === 'dog' ? 30 : 40);
    },
    isDefault() {
        return Number(this.years) === 0 && Number(this.months) === 0;
    },
    humanAge() {
        const years = Math.max(0, Math.floor(Number(this.years)));
        const months = Math.max(0, Math.min(11, Math.floor(Number(this.months))));
        const increment = this.petType === 'cat' ? 4 : ({ small: 4, medium: 5, large: 6, giant: 7 }[this.dogSize] ?? 4);
        let age;

        if (years === 0) {
            age = (months / 12) * 15;
        } else if (years === 1) {
            age = 15 + (months / 12) * 9;
        } else {
            age = 15 + 9 + (years - 2) * increment + (months / 12) * increment;
        }

        return Math.round(age);
    },
    lifeStage() {
        const totalMonths = Number(this.years) * 12 + Number(this.months);
        if (this.petType === 'cat') {
            if (totalMonths < 12) return { label: 'Kitten', description: 'Rapid growth and development. Core vaccinations and socialization occur now.', color: 'bg-amber-100 text-amber-800' };
            if (Number(this.years) <= 6) return { label: 'Young Adult', description: 'Physically mature. Peak energy and activity.', color: 'bg-sky-100 text-sky-800' };
            if (Number(this.years) <= 10) return { label: 'Mature Adult', description: 'Fully mature. Monitor for weight, dental, and kidney health.', color: 'bg-primary/15 text-primary' };
            return { label: 'Senior', description: 'Increased veterinary monitoring recommended. Watch for age-related conditions.', color: 'bg-orange-100 text-orange-800' };
        }

        const boundaries = {
            small: { puppyEnd: 9, youngAdultEnd: 48, matureAdultEnd: 120 },
            medium: { puppyEnd: 9, youngAdultEnd: 48, matureAdultEnd: 108 },
            large: { puppyEnd: 12, youngAdultEnd: 36, matureAdultEnd: 96 },
            giant: { puppyEnd: 12, youngAdultEnd: 36, matureAdultEnd: 72 },
        }[this.dogSize];

        if (totalMonths <= boundaries.puppyEnd) return { label: 'Puppy', description: 'Rapid growth phase. Training and socialization are critical.', color: 'bg-amber-100 text-amber-800' };
        if (totalMonths <= boundaries.youngAdultEnd) return { label: 'Young Adult', description: 'Physically and socially maturing. Peak energy and activity.', color: 'bg-sky-100 text-sky-800' };
        if (totalMonths <= boundaries.matureAdultEnd) return { label: 'Mature Adult', description: 'Fully mature. Monitor for age-related changes in weight, mobility, and health.', color: 'bg-primary/15 text-primary' };
        return { label: 'Senior', description: 'Last 25% of estimated lifespan. Increased veterinary monitoring recommended.', color: 'bg-orange-100 text-orange-800' };
    },
});

const costCalculator = (data) => ({
    petType: 'dog',
    petSize: 'medium',
    service: 'boarding',
    duration: 7,
    petTypes: data.pet_types,
    services: data.services,
    rates: data.rates,
    fallbackRate: data.fallback_rate,
    validSizes() {
        return this.petTypes[this.petType].sizes;
    },
    effectiveSize() {
        return this.validSizes().includes(this.petSize) ? this.petSize : this.validSizes()[0];
    },
    setPetType(type) {
        this.petType = type;
    },
    estimate() {
        const rates = this.rates[this.service]?.[this.effectiveSize()] ?? this.fallbackRate;
        const multiplier = this.service === 'grooming' || this.service === 'vet' ? 1 : Number(this.duration);
        return { min: rates[0] * multiplier, max: rates[1] * multiplier };
    },
    formatNaira(value) {
        return `₦${Number(value).toLocaleString()}`;
    },
});

const nutritionStages = {
    Dog: ['Puppy (under 1 year)', 'Adult (1-7 years)', 'Senior (7+ years)'],
    Cat: ['Kitten (under 1 year)', 'Adult (1-10 years)', 'Senior (10+ years)'],
};

const nutritionCalculator = () => ({
    species: 'Dog',
    weight: '',
    unit: 'kg',
    lifeStage: nutritionStages.Dog[1],
    activity: 'Moderate',
    stages: nutritionStages,
    activities: ['Low', 'Moderate', 'High'],
    changeSpecies(species) {
        this.species = species;
        this.lifeStage = this.stages[species][1];
    },
    weightKg() {
        const weight = Number.parseFloat(this.weight);
        if (!Number.isFinite(weight) || weight <= 0) return null;
        return this.unit === 'lb' ? weight * 0.453592 : weight;
    },
    multiplier() {
        const stage = this.lifeStage.split(' ')[0];
        return ({
            Puppy: 3,
            Kitten: 2.5,
            'Adult Low': 1.2,
            'Adult Moderate': 1.6,
            'Adult High': 2,
            'Senior Low': 1,
            'Senior Moderate': 1.4,
            'Senior High': 1.8,
        }[`${stage} ${this.activity}`] ?? 1.4);
    },
    result() {
        const weightKg = this.weightKg();
        if (weightKg === null) return null;
        const rer = 70 * Math.pow(weightKg, 0.75);
        return { rer: Math.round(rer), dailyCalories: Math.round(rer * this.multiplier()), multiplier: this.multiplier() };
    },
});

const breedFinder = (breeds) => ({
    breeds,
    species: 'dog',
    size: '',
    exerciseNeeds: '',
    groomingNeeds: '',
    search: '',
    debounceTimer: null,
    init() {
        const params = new URLSearchParams(window.location.search);
        this.species = ['dog', 'cat'].includes(params.get('species')) ? params.get('species') : 'dog';
        const sizes = [...new Set(this.breeds.map((breed) => breed.size))];
        const exerciseNeeds = [...new Set(this.breeds.map((breed) => breed.exerciseNeeds))];
        const groomingNeeds = [...new Set(this.breeds.map((breed) => breed.groomingNeeds))];
        this.size = sizes.includes(params.get('size')) ? params.get('size') : '';
        this.exerciseNeeds = exerciseNeeds.includes(params.get('exerciseNeeds')) ? params.get('exerciseNeeds') : '';
        this.groomingNeeds = groomingNeeds.includes(params.get('groomingNeeds')) ? params.get('groomingNeeds') : '';
        this.search = params.get('search') ?? '';
        this.$watch('species', () => this.syncUrl());
        this.$watch('size', () => this.syncUrl());
        this.$watch('exerciseNeeds', () => this.syncUrl());
        this.$watch('groomingNeeds', () => this.syncUrl());
        this.$watch('search', () => this.syncUrl());
        this.syncUrl();
    },
    syncUrl() {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
            const params = new URLSearchParams();
            params.set('species', this.species);
            if (this.size) params.set('size', this.size);
            if (this.exerciseNeeds) params.set('exerciseNeeds', this.exerciseNeeds);
            if (this.groomingNeeds) params.set('groomingNeeds', this.groomingNeeds);
            if (this.search.trim()) params.set('search', this.search.trim());
            const query = params.toString();
            history.replaceState(null, '', `${window.location.pathname}${query ? `?${query}` : ''}`);
        }, 350);
    },
    changeSpecies(value) {
        const search = this.search;
        this.species = value;
        this.size = '';
        this.exerciseNeeds = '';
        this.groomingNeeds = '';
        this.search = search;
    },
    clearFilters() {
        this.size = '';
        this.exerciseNeeds = '';
        this.groomingNeeds = '';
        this.search = '';
    },
    filteredBreeds() {
        const term = this.search.trim().toLowerCase();
        return this.breeds.filter((breed) => {
            if (breed.species !== this.species) return false;
            if (this.size && breed.size !== this.size) return false;
            if (this.exerciseNeeds && breed.exerciseNeeds !== this.exerciseNeeds) return false;
            if (this.groomingNeeds && breed.groomingNeeds !== this.groomingNeeds) return false;
            if (term && !`${breed.name} ${breed.temperament.join(' ')} ${breed.overview}`.toLowerCase().includes(term)) return false;
            return true;
        });
    },
    speciesTotal() {
        return this.breeds.filter((breed) => breed.species === this.species).length;
    },
    hasActiveFilters() {
        return Boolean(this.size || this.exerciseNeeds || this.groomingNeeds || this.search.trim());
    },
    sizeOptions() {
        return ['small', 'medium', 'large', 'giant'].filter((value) => this.breeds.some((breed) => breed.size === value));
    },
    exerciseOptions() {
        return ['low', 'moderate', 'high', 'very-high'].filter((value) => this.breeds.some((breed) => breed.exerciseNeeds === value));
    },
    groomingOptions() {
        return ['low', 'moderate', 'high'].filter((value) => this.breeds.some((breed) => breed.groomingNeeds === value));
    },
    sizeLabel(value) {
        return ({ small: 'Small', medium: 'Medium', large: 'Large', giant: 'Giant' })[value] ?? value;
    },
    exerciseLabel(value) {
        return ({ low: 'Low', moderate: 'Moderate', high: 'High', 'very-high': 'Very high' })[value] ?? value;
    },
    groomingLabel(value) {
        return ({ low: 'Low', moderate: 'Moderate', high: 'High' })[value] ?? value;
    },
});

const behaviorTips = (tips) => ({
    tips,
    species: 'dog',
    query: '',
    filteredTips() {
        const term = this.query.trim().toLowerCase();
        return this.tips.filter((tip) => tip.species === this.species && `${tip.title} ${tip.description}`.toLowerCase().includes(term));
    },
});

const symptomChecker = (data) => ({
    data,
    petType: null,
    selectedArea: null,
    selectedSymptoms: [],
    guidance: null,
    selectPetType(type) {
        this.petType = type;
        this.selectedArea = null;
        this.selectedSymptoms = [];
        this.guidance = null;
    },
    selectArea(area) {
        this.selectedArea = area;
        this.selectedSymptoms = [];
        this.guidance = null;
    },
    toggleSymptom(id) {
        this.selectedSymptoms = this.selectedSymptoms.includes(id)
            ? this.selectedSymptoms.filter((item) => item !== id)
            : [...this.selectedSymptoms, id];
        this.guidance = null;
    },
    checkSymptoms() {
        if (!this.selectedArea || this.selectedSymptoms.length === 0) return;
        const count = this.selectedSymptoms.length;
        const hasHigh = this.selectedSymptoms.some((id) => this.data.high_severity_ids.includes(id));
        const severity = hasHigh || count >= 4 ? 'high' : count >= 2 ? 'moderate' : 'low';
        this.guidance = { severity, ...this.data.guidance[severity] };
    },
    clearSelection() {
        this.selectedSymptoms = [];
        this.guidance = null;
    },
    startOver() {
        this.petType = null;
        this.selectedArea = null;
        this.selectedSymptoms = [];
        this.guidance = null;
    },
});

const newPetChecklist = (categories) => ({
    categories,
    checked: {},
    storageKey: 'waggies-new-pet-checklist',
    init() {
        try {
            const raw = localStorage.getItem(this.storageKey);
            this.checked = raw ? JSON.parse(raw) : {};
        } catch {
            this.checked = {};
        }
    },
    allItems() {
        return this.categories.flatMap((category) => category.items);
    },
    total() {
        return this.allItems().length;
    },
    completed() {
        return this.allItems().filter((item) => this.checked[item.id]).length;
    },
    percentage() {
        return this.total() > 0 ? Math.round((this.completed() / this.total()) * 100) : 0;
    },
    toggle(id) {
        this.checked = { ...this.checked, [id]: !this.checked[id] };
        try {
            localStorage.setItem(this.storageKey, JSON.stringify(this.checked));
        } catch {
            // localStorage unavailable
        }
    },
});

export function registerToolComponents(Alpine) {
    Alpine.data('petAgeCalculator', petAgeCalculator);
    Alpine.data('costCalculator', costCalculator);
    Alpine.data('nutritionCalculator', nutritionCalculator);
    Alpine.data('breedFinder', breedFinder);
    Alpine.data('behaviorTips', behaviorTips);
    Alpine.data('symptomChecker', symptomChecker);
    Alpine.data('newPetChecklist', newPetChecklist);
}
