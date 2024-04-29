import '../../../assets/dist/styles.css';

import { startStimulusApp } from '@symfony/stimulus-bundle';
import FileManager from '@mezcalito/ux-filemanager';

const app = startStimulusApp();

app.register('filemanager', FileManager);