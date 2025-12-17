export default function Card({ icon, value, title, isLoading }) {

  if(isLoading == true) {
    return (
      <div className="giftflow-overview__stat-card giftflow-overview__stat-card--loading">
        <div className="giftflow-overview__stat-icon skeleton" />
        <div className="giftflow-overview__stat-content">
          <span className="giftflow-overview__stat-value skeleton" />
          <span className="giftflow-overview__stat-label skeleton" />
        </div>
      </div>
    );
  }

  return (
    <div className="giftflow-overview__stat-card">
      <div className="giftflow-overview__stat-icon">{ icon }</div>
      <div className="giftflow-overview__stat-content">
        <span className="giftflow-overview__stat-value __monospace" dangerouslySetInnerHTML={{ __html: value }}></span>
        <span className="giftflow-overview__stat-label" dangerouslySetInnerHTML={{ __html: title }}></span>
      </div>
    </div>
  );
}