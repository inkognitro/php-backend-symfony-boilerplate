import SwaggerUI from 'swagger-ui';
import 'swagger-ui/dist/swagger-ui.css'
export default SwaggerUI({
    dom_id: '#swaggerDocs',
    url: '%specificationUrl%',
});
