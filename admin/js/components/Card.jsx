export default function Card({ icon, value, title, isLoading }) {

  if(isLoading == true) {
    return (
      <div className="giftflowwp-overview__stat-card giftflowwp-overview__stat-card--loading">
        <div className="giftflowwp-overview__stat-icon skeleton" />
        <div className="giftflowwp-overview__stat-content">
          <span className="giftflowwp-overview__stat-value skeleton" />
          <span className="giftflowwp-overview__stat-label skeleton" />
        </div>
      </div>
    );
  }

  return (
    <div className="giftflowwp-overview__stat-card">
      <div className="giftflowwp-overview__stat-icon">{ icon }</div>
      <div className="giftflowwp-overview__stat-content">
        <span className="giftflowwp-overview__stat-value __monospace" dangerouslySetInnerHTML={{ __html: value }}></span>
        <span className="giftflowwp-overview__stat-label" dangerouslySetInnerHTML={{ __html: title }}></span>
      </div>
    </div>
  );
}