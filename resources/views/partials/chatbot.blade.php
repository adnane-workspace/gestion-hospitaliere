<!-- Floating Chatbot Widget -->
<style>
    /* Force template tags to be completely invisible and take zero space */
    template {
        display: none !important;
    }
    /* Custom thin scrollbar for chat history */
    #chatbot-history-container::-webkit-scrollbar {
        width: 6px;
    }
    #chatbot-history-container::-webkit-scrollbar-track {
        background: transparent;
    }
    #chatbot-history-container::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 99px;
    }
    #chatbot-history-container::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
    /* Hide scrollbars for suggestions carousel */
    .scrollbar-none::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-none {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

<div x-data="{ 
    open: false, 
    role: '{{ auth()->user()->role }}',
    messages: [], 
    typing: false, 
    input: '' 
}" x-init="
    let welcomeText = '';
    if (role === 'admin') {
        welcomeText = 'Bonjour **{{ auth()->user()->name }}** ! 👋<br>En tant qu\'**Administrateur**, je suis votre guide de gestion. Comment puis-je vous aider aujourd\'hui ?';
    } else if (role === 'medecin') {
        welcomeText = 'Bonjour Docteur **{{ auth()->user()->name }}** ! 🩺<br>En tant que **Médecin**, je vous accompagne dans le suivi de vos patients et vos consultations. Que souhaitez-vous faire ?';
    } else {
        welcomeText = 'Bonjour **{{ auth()->user()->name }}** ! 👋<br>En tant que **Patient**, je vous guide pour prendre rendez-vous, consulter votre dossier ou contacter votre médecin. Comment puis-je vous aider ?';
    }
    messages.push({
        sender: 'assistant',
        text: welcomeText,
        hasGuides: true
    });
" class="fixed bottom-6 right-6 z-[9999] font-sans">
    
    <!-- Floating Circular Button -->
    <button @click="open = !open" 
            class="w-14 h-14 bg-gradient-to-tr from-indigo-600 to-indigo-500 hover:from-indigo-700 hover:to-indigo-600 text-white rounded-full flex items-center justify-center shadow-xl shadow-indigo-200 hover:shadow-indigo-300/50 hover:scale-105 active:scale-95 transition-all duration-300 relative group border border-indigo-400/20">
        <i data-lucide="help-circle" class="w-6 h-6 group-hover:rotate-12 transition-transform duration-300" x-show="!open"></i>
        <i data-lucide="x" class="w-6 h-6 transition-transform duration-300" x-show="open" x-cloak></i>
        <!-- Ring Glow -->
        <span class="absolute inset-0 rounded-full bg-indigo-500/20 animate-ping pointer-events-none -z-10 group-hover:animate-none"></span>
    </button>

    <!-- Chat Window Drawer -->
    <div x-cloak x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-250" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-8 scale-95"
         class="fixed bottom-24 right-6 w-[22rem] sm:w-[26rem] h-[35rem] bg-white/95 backdrop-blur-xl border border-slate-200/60 rounded-3xl shadow-2xl flex flex-col overflow-hidden shadow-indigo-100/50">
        
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-between border-b border-indigo-500/10 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white/10 flex items-center justify-center border border-white/15 shadow-inner">
                    <i data-lucide="bot" class="w-5 h-5 text-white"></i>
                </div>
                <div>
                    <h3 class="font-bold text-sm leading-none tracking-tight">HospitAssist</h3>
                    <div class="flex items-center gap-1.5 mt-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-indigo-100 uppercase tracking-widest" x-text="role === 'admin' ? 'Espace Admin' : (role === 'medecin' ? 'Espace Médecin' : 'Espace Patient')"></span>
                    </div>
                </div>
            </div>
            <button @click="open = false" class="w-7 h-7 rounded-lg bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all text-white border border-white/5">
                <i data-lucide="minus" class="w-4 h-4"></i>
            </button>
        </div>

        <!-- Chat History -->
        <div class="flex-grow py-5 px-4 overflow-y-auto space-y-5 scroll-smooth" id="chatbot-history-container">
            <template x-for="(msg, index) in messages">
                <div class="flex flex-col w-full" :class="msg.sender === 'user' ? 'items-end' : 'items-start'">
                    
                    <div class="flex items-start gap-2 w-full" :class="msg.sender === 'user' ? 'justify-end max-w-[85%]' : 'w-full'">
                        <!-- Avatar for Assistant -->
                        <template x-if="msg.sender === 'assistant'">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mt-1 flex-shrink-0 shadow-sm">
                                <i data-lucide="bot" class="w-4 h-4"></i>
                            </div>
                        </template>

                        <!-- Message Content -->
                        <div class="rounded-2xl px-4 py-3 text-sm leading-relaxed" 
                             :class="msg.sender === 'user' 
                                ? 'bg-indigo-600 text-white rounded-tr-none font-medium shadow-md shadow-indigo-100/50' 
                                : 'bg-slate-50 text-slate-800 border border-slate-200/50 rounded-tl-none shadow-sm w-full'">
                            <div x-html="msg.text"></div>

                            <!-- Clickable Guides under Assistant message -->
                            <template x-if="msg.hasGuides">
                                <div class="mt-4 flex flex-col gap-2.5">
                                    
                                    <!-- ADMIN & MEDECIN: Créer un Patient -->
                                    <template x-if="role === 'admin' || role === 'medecin'">
                                        <button @click="input = 'Créer un patient'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center font-bold text-sm">➕</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Créer un Patient</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Enregistrer un nouveau dossier</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- PATIENT & ADMIN: Prendre Rendez-vous -->
                                    <template x-if="role === 'patient' || role === 'admin'">
                                        <button @click="input = 'Prendre un rdv'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-500 flex items-center justify-center font-bold text-sm">📅</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Prendre un Rendez-vous</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Réserver une consultation</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- MEDECIN: Rédiger Consultation -->
                                    <template x-if="role === 'medecin'">
                                        <button @click="input = 'Rédiger une ordonnance'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center font-bold text-sm">🩺</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Rédiger une Consultation</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Ordonnances & diagnostics</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- MEDECIN: Gérer Disponibilités -->
                                    <template x-if="role === 'medecin'">
                                        <button @click="input = 'Gérer mes disponibilités'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-teal-50 text-teal-500 flex items-center justify-center font-bold text-sm">⏰</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Mes Disponibilités</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Configurer vos plages de gardes</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- ADMIN: Gérer Comptabilité / Facturation -->
                                    <template x-if="role === 'admin'">
                                        <button @click="input = 'Gérer la facturation'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-amber-50 text-amber-500 flex items-center justify-center font-bold text-sm">💳</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Factures & Mutuelle (MAD)</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Suivi comptable de la clinique</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- ADMIN: Gérer Médecins -->
                                    <template x-if="role === 'admin'">
                                        <button @click="input = 'Gérer les médecins'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-violet-50 text-violet-500 flex items-center justify-center font-bold text-sm">👨‍⚕️</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Gestion des Médecins</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Activer ou valider les comptes</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- PATIENT: Mon Dossier Médical -->
                                    <template x-if="role === 'patient'">
                                        <button @click="input = 'Dossier medical'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-sky-50 text-sky-500 flex items-center justify-center font-bold text-sm">📁</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Mon Dossier Médical</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Allergies, IMC, antécédents</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- PATIENT: Exporter PDF -->
                                    <template x-if="role === 'patient'">
                                        <button @click="input = 'Exporter PDF'; sendMessage($data)" 
                                                class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                            <span class="flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-xl bg-red-50 text-red-500 flex items-center justify-center font-bold text-sm">📥</span>
                                                <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                    <span class="font-bold text-slate-850 group-hover:text-indigo-600">Exporter mon Historique</span>
                                                    <span class="text-[9px] text-slate-400 font-medium mt-0.5">Télécharger votre bilan en PDF</span>
                                                </span>
                                            </span>
                                            <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                        </button>
                                    </template>

                                    <!-- COMMON: Messagerie -->
                                    <button @click="input = 'Messagerie'; sendMessage($data)" 
                                            class="w-full text-left p-3 bg-white hover:bg-indigo-50/30 border border-slate-100 hover:border-indigo-100 rounded-2xl text-xs font-semibold text-slate-700 shadow-sm transition-all flex items-center justify-between group">
                                        <span class="flex items-center gap-3">
                                            <span class="w-8 h-8 rounded-xl bg-blue-50 text-blue-500 flex items-center justify-center font-bold text-sm">💬</span>
                                            <span class="group-hover:translate-x-0.5 transition-transform flex flex-col">
                                                <span class="font-bold text-slate-850 group-hover:text-indigo-600">Messagerie Sécurisée</span>
                                                <span class="text-[9px] text-slate-400 font-medium mt-0.5">Contacter vos médecins/patients</span>
                                            </span>
                                        </span>
                                        <i data-lucide="chevron-right" class="w-4 h-4 text-slate-300 group-hover:text-indigo-500 group-hover:translate-x-0.5 transition-all"></i>
                                    </button>

                                </div>
                            </template>

                            <!-- Inline Action Button (if present) -->
                            <template x-if="msg.linkUrl">
                                <div class="mt-4 pt-3 border-t border-slate-200/50 flex justify-end">
                                    <a :href="msg.linkUrl" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold tracking-wide transition-all shadow-md shadow-indigo-100 hover:shadow-indigo-200 cursor-pointer active:scale-95">
                                        <i data-lucide="external-link" class="w-3.5 h-3.5"></i>
                                        <span x-text="msg.linkLabel"></span>
                                    </a>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Typing indicator -->
            <div class="flex items-start gap-2.5" x-show="typing" x-cloak>
                <div class="w-8 h-8 rounded-lg bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 mt-1 flex-shrink-0 shadow-sm">
                    <i data-lucide="bot" class="w-4 h-4"></i>
                </div>
                <div class="bg-slate-50 border border-slate-200/50 rounded-2xl rounded-tl-none px-4 py-3 flex items-center gap-1.5 shadow-sm">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.1s"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.2s"></span>
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-400 animate-bounce" style="animation-delay: 0.3s"></span>
                </div>
            </div>
        </div>

        <!-- Input Box -->
        <div class="p-4 bg-white border-t border-slate-100/80">
            <div class="relative flex items-center bg-slate-50 border border-slate-200/80 rounded-2xl focus-within:border-indigo-500 focus-within:ring-4 focus-within:ring-indigo-500/10 focus-within:bg-white transition-all duration-300 pr-12 shadow-sm">
                <input type="text" x-model="input" @keydown.enter="sendMessage($data)" placeholder="Posez une question sur le projet..."
                       class="w-full pl-4 pr-2 py-3.5 bg-transparent border-none outline-none focus:ring-0 text-sm font-medium text-slate-800 placeholder-slate-400">
                <button @click="sendMessage($data)"
                        class="absolute right-1.5 top-1.5 bottom-1.5 w-9 h-9 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center transition-all shadow-md shadow-indigo-200 active:scale-95 cursor-pointer">
                    <i data-lucide="send" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Local Knowledge Matching Engine for guides with beautiful HTML layout
    const GUIDES_DATABASE = {
        patient: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">📊</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Enregistrer un Nouveau Patient</h4>` +
                  `    <span class="px-2 py-0.5 bg-rose-100 text-rose-700 text-[9px] font-bold rounded-full ml-auto">ADMIN / MÉDECIN</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Voici les étapes pour créer un nouveau dossier patient :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Ouvrez <strong>Gestion Patients</strong> dans le menu de gauche.</li>` +
                  `    <li>Cliquez sur le bouton <strong>+ Enregistrer un Nouveau Patient</strong>.</li>` +
                  `    <li>Saisissez l'identité (Nom, Prénom, Genre, Date de naissance).</li>` +
                  `    <li>Indiquez les identifiants requis : <strong>N° de dossier</strong> (ex: \`DOS-2026-XXXX\`) et <strong>CIN</strong>.</li>` +
                  `    <li>Complétez son numéro de téléphone puis validez.</li>` +
                  `  </ol>` +
                  `  <p class="text-[11px] text-slate-500 italic mt-2">💡 Une fois le dossier créé, vous pourrez configurer sa mutuelle (CNOPS, CNSS, AMO), ses allergies, antécédents et rédiger des consultations.</p>` +
                  `</div>`,
            linkLabel: "Ouvrir le formulaire d'ajout",
            linkUrl: "/patients/create"
        },
        rdv: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">📅</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Prendre un Rendez-vous</h4>` +
                  `    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-full ml-auto">PATIENT / ADMIN</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Voici comment réserver une consultation médicale :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Connectez-vous en tant que <strong>Patient</strong> (ou administrateur).</li>` +
                  `    <li>Cliquez sur le menu <strong>Prendre RDV</strong>.</li>` +
                  `    <li>Sélectionnez le médecin traitant ou le service de spécialité.</li>` +
                  `    <li>Renseignez le motif du rendez-vous et le canal (présentiel, téléphone, en ligne).</li>` +
                  `    <li>Sélectionnez la plage horaire disponible et validez la prise de rendez-vous.</li>` +
                  `  </ol>` +
                  `  <p class="text-[11px] text-slate-500 italic mt-2">💡 Une notification instantanée sera envoyée au médecin concerné et le rendez-vous s'affichera sur son tableau de bord.</p>` +
                  `</div>`,
            linkLabel: "Prendre un RDV",
            linkUrl: "/patient/rendezvous/nouveau"
        },
        consultation: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">🩺</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Rédiger une Consultation</h4>` +
                  `    <span class="px-2 py-0.5 bg-indigo-100 text-indigo-700 text-[9px] font-bold rounded-full ml-auto">MÉDECIN</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Méthodologie clinique pour consigner une visite :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Rendez-vous dans <strong>Mes Rendez-vous</strong> ou <strong>Mes Patients</strong>.</li>` +
                  `    <li>Sélectionnez le patient et cliquez sur <strong>Dossier Médical</strong>.</li>` +
                  `    <li>Cliquez sur le bouton <strong>Ajouter une Consultation</strong>.</li>` +
                  `    <li>Prenez les <strong>constantes physiques</strong> (Poids, Taille, Température, Tension). L'**IMC** est calculé en temps réel !</li>` +
                  `    <li>Associez un diagnostic de la nomenclature <strong>CIM-10</strong> (ex: \`I10\` pour HTA).</li>` +
                  `    <li>Remplissez les lignes d'ordonnance (médicaments marocains pré-configurés, posologie et durée).</li>` +
                  `  </ol>` +
                  `</div>`,
            linkLabel: "Mes Consultations",
            linkUrl: "/medecin/consultations"
        },
        disponibilite: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">⏰</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Gérer mes Disponibilités</h4>` +
                  `    <span class="px-2 py-0.5 bg-teal-100 text-teal-700 text-[9px] font-bold rounded-full ml-auto">MÉDECIN</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Comment configurer vos horaires hebdomadaires :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Ouvrez le menu <strong>Disponibilités</strong> dans votre espace Médecin.</li>` +
                  `    <li>Définissez vos heures d'entrée et de sortie pour chaque jour de garde.</li>` +
                  `    <li>Enregistrez pour permettre aux patients de réserver des créneaux valides.</li>` +
                  `  </ol>` +
                  `</div>`,
            linkLabel: "Mes Disponibilités",
            linkUrl: "/medecin/disponibilites"
        },
        facture: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">💳</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Facturation & Mutuelles (MAD)</h4>` +
                  `    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-[9px] font-bold rounded-full ml-auto">ADMINISTRATEUR</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Gestion de la comptabilité et calculs de tiers-payant :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Allez sur le menu <strong>Comptabilité</strong> dans la barre latérale.</li>` +
                  `    <li>Consultez les états des facturations (Payée, En retard, Partielle).</li>` +
                  `    <li>Lors de la facturation, liez une mutuelle : **CNSS**, **CNOPS** ou **AMO** (couverture automatique à 70-80%).</li>` +
                  `    <li>Le système calcule automatiquement la part mutuelle remboursée, la TVA à 10% et le ticket modérateur restant à la charge du patient.</li>` +
                  `  </ol>` +
                  `</div>`,
            linkLabel: "Ouvrir la Comptabilité",
            linkUrl: "/admin/comptabilite"
        },
        medecins: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">👨‍⚕️</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Gestion des Médecins</h4>` +
                  `    <span class="px-2 py-0.5 bg-violet-100 text-violet-700 text-[9px] font-bold rounded-full ml-auto">ADMINISTRATEUR</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Validation et gestion des comptes praticiens :</p>` +
                  `  <ul class="space-y-1.5 text-xs text-slate-705">` +
                  `    <li>🔹 Accédez à <strong>Gestion Médecins</strong> pour voir la liste complète.</li>` +
                  `    <li>🔹 Allez sur <strong>Médecins en attente</strong> pour voir les inscriptions soumises.</li>` +
                  `    <li>🔹 Cliquez sur <strong>Activer</strong> pour autoriser le médecin à se connecter et recevoir des patients.</li>` +
                  `  </ul>` +
                  `</div>`,
            linkLabel: "Gérer les Médecins",
            linkUrl: "/admin/medecins"
        },
        dossier: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">📁</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Mon Dossier Médical</h4>` +
                  `    <span class="px-2 py-0.5 bg-sky-100 text-sky-700 text-[9px] font-bold rounded-full ml-auto">PATIENT</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Consultez vos constantes cliniques et antécédents :</p>` +
                  `  <ul class="space-y-1.5 text-xs text-slate-705">` +
                  `    <li>🔹 Accédez à <strong>Mon Profil Patient</strong> dans votre espace.</li>` +
                  `    <li>🔹 Vous y trouverez votre **groupe sanguin**, vos **allergies déclarées**, et vos **antécédents médicaux/chirurgicaux**.</li>` +
                  `    <li>🔹 Le système calcule votre **IMC** à partir de votre poids et taille.</li>` +
                  `  </ul>` +
                  `</div>`,
            linkLabel: "Consulter mon Dossier",
            linkUrl: "/patient/profile"
        },
        pdf: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">📥</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Exporter mon Bilan Clinique PDF</h4>` +
                  `    <span class="px-2 py-0.5 bg-red-100 text-red-700 text-[9px] font-bold rounded-full ml-auto">PATIENT</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Générez un rapport PDF exportable contenant tout votre historique médical :</p>` +
                  `  <ul class="space-y-1.5 text-xs text-slate-705">` +
                  `    <li>🔹 Connectez-vous avec votre profil Patient.</li>` +
                  `    <li>🔹 Cliquez sur le bouton <strong>Exporter Historique PDF</strong>.</li>` +
                  `    <li>🔹 Un document officiel au format PDF incluant vos constantes, vos diagnostics CIM-10, et vos prescriptions est généré instantanément.</li>` +
                  `  </ul>` +
                  `</div>`,
            linkLabel: "Télécharger mon PDF",
            linkUrl: "/patient/historique/pdf"
        },
        message: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">💬</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Messagerie Sécurisée</h4>` +
                  `    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-[9px] font-bold rounded-full ml-auto">TOUS LES RÔLES</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Discutez de manière sécurisée en temps réel :</p>` +
                  `  <ol class="space-y-2 text-xs text-slate-705 list-decimal list-inside pl-1">` +
                  `    <li>Cliquez sur le menu <strong>Messagerie</strong> dans le volet gauche.</li>` +
                  `    <li>Sélectionnez votre correspondant (médecin ou patient).</li>` +
                  `    <li>Saisissez votre message dans l'éditeur et envoyez.</li>` +
                  `  </ol>` +
                  `  <p class="text-[11px] text-slate-500 italic mt-2">💡 Une alerte de notification rouge s'affiche dynamiquement lors de la réception d'un nouveau message.</p>` +
                  `</div>`,
            linkLabel: "Ouvrir la Messagerie",
            linkUrl: "/messages"
        },
        notification: {
            text: `<div class="space-y-3">` +
                  `  <div class="flex items-center gap-2 pb-2 border-b border-slate-200/60">` +
                  `    <span class="text-lg">🔔</span>` +
                  `    <h4 class="font-bold text-slate-900 leading-tight">Système de Notifications</h4>` +
                  `    <span class="px-2 py-0.5 bg-slate-100 text-slate-700 text-[9px] font-bold rounded-full ml-auto">TOUS LES RÔLES</span>` +
                  `  </div>` +
                  `  <p class="text-xs text-slate-600">Suivi des événements importants :</p>` +
                  `  <ul class="space-y-1.5 text-xs text-slate-705">` +
                  `    <li>🔹 Un voyant rouge s'affiche sur la cloche 🔔 en haut à droite en cas de nouveau rendez-vous, confirmation ou facture.</li>` +
                  `    <li>🔹 Cliquez sur l'icône pour dérouler les alertes en temps réel.</li>` +
                  `    <li>🔹 Cliquez sur <strong>Tout marquer lu</strong> pour archiver les notifications.</li>` +
                  `  </ul>` +
                  `</div>`,
            linkLabel: "Retour au Dashboard",
            linkUrl: "/dashboard"
        }
    };

    function sendMessage(state) {
        if (!state.input.trim()) return;

        const userText = state.input.trim();
        state.messages.push({ sender: 'user', text: userText });
        state.input = '';
        state.typing = true;

        // Scroll to bottom
        setTimeout(() => {
            const container = document.getElementById('chatbot-history-container');
            if (container) container.scrollTop = container.scrollHeight;
        }, 50);

        // Normalize text for matching
        const norm = userText.toLowerCase();
        let reply = null;

        // Keyword mapping
        if (norm.includes('patient') || norm.includes('creer') || norm.includes('créer') || norm.includes('ajouter')) {
            reply = GUIDES_DATABASE.patient;
        } else if (norm.includes('rdv') || norm.includes('rendez-vous') || norm.includes('prendre') || norm.includes('agenda') || norm.includes('calendrier')) {
            reply = GUIDES_DATABASE.rdv;
        } else if (norm.includes('consultation') || norm.includes('ordonnance') || norm.includes('médicament') || norm.includes('cim')) {
            reply = GUIDES_DATABASE.consultation;
        } else if (norm.includes('dispo') || norm.includes('disponibilité') || norm.includes('heure') || norm.includes('horaire') || norm.includes('garde') || norm.includes('planning')) {
            reply = GUIDES_DATABASE.disponibilite;
        } else if (norm.includes('facture') || norm.includes('compta') || norm.includes('argent') || norm.includes('pay') || norm.includes('mutuelle') || norm.includes('cnss') || norm.includes('cnops') || norm.includes('amo') || norm.includes('dirham') || norm.includes('mad')) {
            reply = GUIDES_DATABASE.facture;
        } else if (norm.includes('medecin') || norm.includes('médecin') || norm.includes('praticien') || norm.includes('activer') || norm.includes('validation')) {
            reply = GUIDES_DATABASE.medecins;
        } else if (norm.includes('dossier') || norm.includes('allergie') || norm.includes('imc') || norm.includes('antécédent')) {
            reply = GUIDES_DATABASE.dossier;
        } else if (norm.includes('pdf') || norm.includes('export') || norm.includes('télécharger') || norm.includes('telecharger') || norm.includes('bilan')) {
            reply = GUIDES_DATABASE.pdf;
        } else if (norm.includes('message') || norm.includes('chat') || norm.includes('envoyer') || norm.includes('ecrire') || norm.includes('discuter')) {
            reply = GUIDES_DATABASE.message;
        } else if (norm.includes('notification') || norm.includes('cloche') || norm.includes('alerte')) {
            reply = GUIDES_DATABASE.notification;
        }

        // Simulate typing animation
        setTimeout(() => {
            state.typing = false;
            if (reply) {
                state.messages.push({
                    sender: 'assistant',
                    text: reply.text,
                    linkLabel: reply.linkLabel,
                    linkUrl: reply.linkUrl,
                    hasGuides: true
                });
            } else {
                let fallbackText = '';
                if (state.role === 'admin') {
                    fallbackText = `<div class="space-y-2">` +
                                   `  <p class="font-semibold text-slate-800">🔍 Désolé, je n'ai pas bien saisi votre demande.</p>` +
                                   `  <p class="text-xs text-slate-500">En tant qu'**Administrateur**, je peux vous aider sur :</p>` +
                                   `  <ul class="text-xs text-slate-700 space-y-1 pl-1">` +
                                   `    <li>• **Créer un Patient** (écrivez "patient")</li>` +
                                   `    <li>• **Valider des Médecins** (écrivez "médecin")</li>` +
                                   `    <li>• **Gérer la Comptabilité** (écrivez "facture")</li>` +
                                   `    <li>• **Messagerie & Chat** (écrivez "message")</li>` +
                                   `  </ul>` +
                                   `</div>`;
                } else if (state.role === 'medecin') {
                    fallbackText = `<div class="space-y-2">` +
                                   `  <p class="font-semibold text-slate-800">🔍 Désolé, je n'ai pas bien saisi votre demande.</p>` +
                                   `  <p class="text-xs text-slate-500">En tant que **Médecin**, je peux vous aider sur :</p>` +
                                   `  <ul class="text-xs text-slate-700 space-y-1 pl-1">` +
                                   `    <li>• **Créer un Patient** (écrivez "patient")</li>` +
                                   `    <li>• **Rédiger des Ordonnances** (écrivez "ordonnance")</li>` +
                                   `    <li>• **Mes Disponibilités / Gardes** (écrivez "disponibilités")</li>` +
                                   `    <li>• **Messagerie & Chat** (écrivez "message")</li>` +
                                   `  </ul>` +
                                   `</div>`;
                } else {
                    fallbackText = `<div class="space-y-2">` +
                                   `  <p class="font-semibold text-slate-800">🔍 Désolé, je n'ai pas bien saisi votre demande.</p>` +
                                   `  <p class="text-xs text-slate-500">En tant que **Patient**, je peux vous aider sur :</p>` +
                                   `  <ul class="text-xs text-slate-700 space-y-1 pl-1">` +
                                   `    <li>• **Prendre un RDV** (écrivez "rdv")</li>` +
                                   `    <li>• **Consulter mon Dossier** (écrivez "dossier")</li>` +
                                   `    <li>• **Exporter mon Historique PDF** (écrivez "pdf")</li>` +
                                   `    <li>• **Messagerie & Chat** (écrivez "message")</li>` +
                                   `  </ul>` +
                                   `</div>`;
                }
                state.messages.push({
                    sender: 'assistant',
                    text: fallbackText,
                    hasGuides: true
                });
            }
            
            // Re-trigger Lucide icons to render them inside dyn content
            setTimeout(() => {
                lucide.createIcons();
                const container = document.getElementById('chatbot-history-container');
                if (container) container.scrollTop = container.scrollHeight;
            }, 50);

        }, 1200);
    }
</script>
