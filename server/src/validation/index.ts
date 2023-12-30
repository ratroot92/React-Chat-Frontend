import { authValidation } from './client/auth.validation';
import { userValidation } from './client/user.validation';
import { cohortValidation } from './admin/cohort.validation';
import { sessionValidation } from './admin/session.validation';
import { clientValidation } from './admin/client.validation';
import { moduleAssignmentValidation } from './admin/moduleAssignment.validation';
import { groupValidation } from './admin/group.validation';
import { postValidation } from './client/post.validations';
import { adminPostValidation } from './admin/post.validation';
import { clientGroupValidations } from './client/group.validations';
const routesValidations: any = { ...authValidation, ...userValidation, ...cohortValidation, ...sessionValidation, ...clientValidation, ...moduleAssignmentValidation, ...groupValidation, ...postValidation, ...adminPostValidation, ...clientGroupValidations };

export { routesValidations };
