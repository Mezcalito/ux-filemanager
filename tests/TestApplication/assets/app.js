import '../../../assets/dist/styles.css';

import { startStimulusApp } from '@symfony/stimulus-bundle';
import { DisplayController, SubmenuController, CollapseController } from '@mezcalito/ux-filemanager';

const app = startStimulusApp();

app.register('mezcalito--ux-filemanager--display', DisplayController);
app.register('mezcalito--ux-filemanager--submenu', SubmenuController);
app.register('mezcalito--ux-filemanager--collapse', CollapseController);