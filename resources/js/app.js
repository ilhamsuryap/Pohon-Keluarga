import './bootstrap';
import Alpine from 'alpinejs';
import { FamilyTree, transformToHierarchy } from './familyTree';

window.Alpine = Alpine;
Alpine.start();

// Make FamilyTree available globally
window.FamilyTree = FamilyTree;
window.transformToHierarchy = transformToHierarchy;
